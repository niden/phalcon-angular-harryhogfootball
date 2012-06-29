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
 * @license     MIT - https://github.com/niden/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

use Phalcon_Tag as Tag;
use niden_Session as Session;

class AwardsController extends ControllerBase
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
                    'Add Award',
                    'class' => 'btn btn-primary'
                )
            );
        }

        $this->view->setVar('addButton', $add);
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

            $episodes    = new Episodes();
            $allEpisodes = $episodes->find(array('order' => 'airDate DESC'));

            $users    = new Users();
            $allUsers = $users->find(array('order' => 'username'));

            $players    = new Players();
            $allPlayers = $players->find(array('order' => 'active DESC, name'));

            $this->view->setVar('users', $allUsers);
            $this->view->setVar('episodes', $allEpisodes);
            $this->view->setVar('players', $allPlayers);

            if ($this->request->isPost()) {

                $award = new Scoring();
                $award->userId    = $this->request->getPost('userId', 'int');
                $award->episodeId = $this->request->getPost('episodeId', 'int');
                $award->playerId  = $this->request->getPost('playerId', 'int');
                $award->award     = $this->request->getPost('award', 'int');

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

                    $this->response->redirect('awards/');
                }

            }
        }
    }

    /**
     * Gets the Hall of Fame
     */
    public function getAction($action)
    {
        $this->view->setRenderLevel(Phalcon_View::LEVEL_LAYOUT);
        $request = $this->request;

        $results = $this->_getHof();

        echo $results;
    }

    /**
     * Gets the Hall of Fame for the home page
     */
    public function hofAction()
    {
        $this->view->setRenderLevel(Phalcon_View::LEVEL_LAYOUT);
        $request = $this->request;

            $results = $this->_getHof(5);

            echo $results;
    }

    /**
     * Private function getting results for the HOF
     *
     * @param  null $limit
     * @return string
     */
    private function _getHof($limit = null)
    {
        $connection = Phalcon_Db_Pool::getConnection();
        $sql = 'SELECT COUNT(s.id) AS total, p.name AS playerName, s.award '
             . 'FROM scoring s '
             . 'INNER JOIN players p ON s.playerId = p.id '
             . 'WHERE s.award = %s '
             . 'GROUP BY s.award, s.playerId '
             . 'ORDER BY s.award ASC, total DESC, p.name ';

        if (!empty($limit)) {
            $sql .= 'LIMIT ' . (int) $limit;
        }

        // Kicks
        $query = sprintf($sql, 0);
        $result = $connection->query($query);
        $result->setFetchMode(Phalcon_Db::DB_ASSOC);

        $kicks    = array();
        $kicksMax = 0;

        while ($item = $result->fetchArray()) {
            $kicksMax = (0 == $kicksMax) ? $item['total'] : $kicksMax;
            $name     = $item['playerName'];
            $kicks[]  = array(
                'total'   => $item['total'],
                'name'    => $name,
                'percent' => (int) ($item['total'] * 100 / $kicksMax),
            );
        }

        // Game balls
        $query = sprintf($sql, 1);
        $result = $connection->query($query);
        $result->setFetchMode(Phalcon_Db::DB_ASSOC);

        $gameballs    = array();
        $gameballsMax = 0;

        while ($item = $result->fetchArray()) {
            $gameballsMax = (0 == $gameballsMax) ?
                            $item['total']       :
                            $gameballsMax;
            $name         = $item['playerName'];
            $gameballs[]  = array(
                'total'   => $item['total'],
                'name'    => $name,
                'percent' => (int) ($item['total'] * 100 / $gameballsMax),
            );
        }

        $result = array('gameballs' => $gameballs, 'kicks' => $kicks);
        return json_encode($result);
    }
}
