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
use \Phalcon\Mvc\View as View;

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

        $auth = $this->session->get('auth');
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
        $this->view->setVar('menus', $this->constructMenu($this));
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
        $auth = $this->session->get('auth');

        if ($auth) {
            $this->_bc->add('Add', 'awards/add');

            $allEpisodes = Episodes::find(array('order' => 'air_date DESC'));
            $allUsers    = Users::find(array('order' => 'username'));
            $allPlayers  = Players::find(array('order' => 'active DESC, name'));

            $this->view->setVar('users', $allUsers);
            $this->view->setVar('episodes', $allEpisodes);
            $this->view->setVar('players', $allPlayers);

            if ($this->request->isPost()) {

                $datetime = date('Y-m-d H:i:s');

                $award = new Awards();
                $award->userId    = $this->request->getPost('user_id', 'int');
                $award->episodeId = $this->request->getPost('episode_id', 'int');
                $award->playerId  = $this->request->getPost('player_id', 'int');
                $award->award     = $this->request->getPost('award', 'int');

                if (!$award->save()) {
                    foreach ($award->getMessages() as $message) {
                        $this->flash->error((string) $message);
                    }
                } else {
                    $this->flash->success('Award created successfully');
                    $this->invalidateCache();
                    $this->response->redirect('awards/');
                }

            }
        }
    }

    /**
     * Gets the Hall of Fame
     */
    public function getAction($action = 0, $limit = null)
    {
        $this->view->setRenderLevel(View::LEVEL_LAYOUT);

        $sql = 'SELECT COUNT(Awards.id) AS total, Players.name AS player_name, Awards.award '
             . 'FROM Awards '
             . 'INNER JOIN Players '
             . 'WHERE Awards.award = %s ';

        switch ($action) {
            case 1:
                $sql .= 'AND Players.active = 1 ';
                break;
            case 2:
            case 3:
            case 4:
                $sql .= 'AND Awards.user_id = ' . (int) $action. ' ';
                break;
        }

        $sql .= 'GROUP BY Awards.award, Awards.player_id '
              . 'ORDER BY Awards.award ASC, total DESC, Players.name ';

        if (!empty($limit)) {
            $sql .= 'LIMIT ' . (int) $limit;
        }

        // Do some data caching based on the query that was sent to us
        $hash   = $this->getCacheHash('model', sha1($sql));
        $result = $this->cache->get($hash);

        // If $result is null then the content will be created or will refreshed
        if ($result === null) {

            // Kicks
            $kickSql = sprintf($sql, -1);
            $query   = $this->modelsManager->createQuery($kickSql);
            $result  = $query->execute();

            $kicks    = array();
            $kicksMax = 0;

            foreach ($result as $item) {
                $kicksMax = (0 == $kicksMax) ? $item->total : $kicksMax;
                $name     = $item->player_name;
                $kicks[]  = array(
                    'total'   => (int) $item->total,
                    'name'    => $name,
                    'percent' => (int) ($item->total * 100 / $kicksMax),
                );
            }

            // Game balls
            $gameSql = sprintf($sql, 1);
            $query   = $this->modelsManager->createQuery($gameSql);
            $result  = $query->execute();

            $gameballs    = array();
            $gameballsMax = 0;

            foreach ($result as $item) {
                $gameballsMax = (0 == $gameballsMax) ?
                                $item->total         :
                                $gameballsMax;
                $name         = $item->player_name;
                $gameballs[]  = array(
                    'total'   => (int) $item->total,
                    'name'    => $name,
                    'percent' => (int) ($item->total * 100 / $gameballsMax),
                );
            }

            $result = json_encode(
                array('gameballs' => $gameballs, 'kicks' => $kicks)
            );

            // Store it in the cache
            $this->cache->save($hash, $result);
        }

        echo $result;
    }

    private function invalidateCache()
    {
        $cacheDir = $this->config->models->cache->cacheDir;
        $name     = strtolower($this->getName());

        foreach (glob($cacheDir . '*' . $name) as $filename) {

            // Remove the path $cacheDir and 'model.'
            $entry = str_replace($cacheDir, '', $filename);

            // $entry has the cache key
            $this->cache->delete($entry);
        }
    }
}
