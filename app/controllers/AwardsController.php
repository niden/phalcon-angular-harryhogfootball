<?php
/**
 * AwardsController.php
 * AwardsController
 *
 * The awards controller and its actions
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-24
 * @category    Controllers
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

use \Phalcon\Tag as Tag;
use \Phalcon\View as View;
use \Phalcon\Db as Db;
use \Phalcon\Db\Pool as DbPool;
use \NDN\Session as Session;
use \NDN\Registry as Registry;

class AwardsController extends \NDN\Controller
{
    /**
     * Initializes the controller
     */
    public function initialize()
    {
        Tag::setTitle('Manage Awards');
        parent::initialize();

        $this->_bc->add('Awards', 'awards');

        $auth = Session::get('auth');
        $add  = '';

        if ($auth) {
            $add = Tag::linkTo(
                array(
                    'awards/add',
                    'Add Award'
                )
            );
        }

        $this->view->setVar('addButton', $add);
        $this->view->setVar('top_menu', $this->constructMenu($this));
    }

    /**
     * The index action
     */
    public function indexAction()
    {
    }

    /**
     * The add action
     */
    public function addAction()
    {
        $auth = Session::get('auth');

        if ($auth) {
            $this->_bc->add('Add', 'awards/add');

            $allEpisodes = Episodes::find(array('order' => 'airDate DESC'));
            $allUsers    = Users::find(array('order' => 'username'));
            $allPlayers  = Players::find(array('order' => 'active DESC, name'));

            $this->view->setVar('users', $allUsers);
            $this->view->setVar('episodes', $allEpisodes);
            $this->view->setVar('players', $allPlayers);

            if ($this->request->isPost()) {

                $datetime = date('Y-m-d H:i:s');

                $award = new Awards();
                $award->userId    = $this->request->getPost('userId', 'int');
                $award->episodeId = $this->request->getPost('episodeId', 'int');
                $award->playerId  = $this->request->getPost('playerId', 'int');
                $award->award     = $this->request->getPost('award', 'int');

                $award->lastUpdate       = $datetime;
                $award->lastUpdateUserId = (int) $auth['id'];

                if (!$award->save()) {
                    foreach ($award->getMessages() as $message) {
                        Session::setFlash(
                            'error',
                            (string) $message,
                            'alert alert-error'
                        );
                    }
                } else {
                    Session::setFlash(
                        'success',
                        'Award created successfully',
                        'alert alert-success'
                    );

                    $this->invalidateCache();

                    $this->response->redirect('awards/');
                }

            }
        }
    }

    /**
     * Gets the Hall of Fame
     */
    public function getAction($action, $limit = null)
    {
        $this->view->setRenderLevel(View::LEVEL_LAYOUT);

        $sql = 'SELECT COUNT(a.id) AS total, p.name AS playerName, a.award '
             . 'FROM awards a '
             . 'INNER JOIN players p ON a.playerId = p.id '
             . 'WHERE a.award = %s ';

        switch ($action) {
            case 1:
                $sql .= 'AND p.active = 1 ';
                break;
            case 2:
            case 3:
            case 4:
                $sql .= 'AND a.userId = ' . (int) $action. ' ';
                break;
        }

        $sql .= 'GROUP BY a.award, a.playerId '
              . 'ORDER BY a.award ASC, total DESC, p.name ';

        if (!empty($limit)) {
            $sql .= 'LIMIT ' . (int) $limit;
        }

        // Do some data caching based on the query that was sent to us
        $hash  = $this->getCacheHash('model', sha1($sql));
        $cache  = Registry::get('cache');
        $result = $cache->get($hash);

        // If $result is null then the content will be created or will refreshed
        if ($result === null) {

            $connection = DbPool::getConnection();

            // Kicks
            $query = sprintf($sql, -1);
            $result = $connection->query($query);
            $result->setFetchMode(Db::DB_ASSOC);

            $kicks    = array();
            $kicksMax = 0;

            while ($item = $result->fetchArray()) {
                $kicksMax = (0 == $kicksMax) ? $item['total'] : $kicksMax;
                $name     = $item['playerName'];
                $kicks[]  = array(
                    'total'   => (int) $item['total'],
                    'name'    => $name,
                    'percent' => (int) ($item['total'] * 100 / $kicksMax),
                );
            }

            // Game balls
            $query = sprintf($sql, 1);
            $result = $connection->query($query);
            $result->setFetchMode(Db::DB_ASSOC);

            $gameballs    = array();
            $gameballsMax = 0;

            while ($item = $result->fetchArray()) {
                $gameballsMax = (0 == $gameballsMax) ?
                                $item['total']       :
                                $gameballsMax;
                $name         = $item['playerName'];
                $gameballs[]  = array(
                    'total'   => (int) $item['total'],
                    'name'    => $name,
                    'percent' => (int) ($item['total'] * 100 / $gameballsMax),
                );
            }

            $result = json_encode(
                array('gameballs' => $gameballs, 'kicks' => $kicks)
            );

            // Store it in the cache
            $cache->save($hash, $result);
        }

        echo $result;
    }

    private function invalidateCache()
    {
        $config   = Registry::get('config');
        $cache    = Registry::get('cache');
        $cacheDir = $config->models->cache->cacheDir;
        $name     = strtolower($this->getName());

        foreach (glob($cacheDir . '*' . $name) as $filename) {

            // Remove the path $cacheDir and 'model.'
            $entry = str_replace($cacheDir, '', $filename);

            // $entry has the cache key
            $cache->remove($entry);
        }
    }
}
