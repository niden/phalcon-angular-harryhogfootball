<?php
/**
 * PlayersController.php
 * PlayersController
 *
 * The players controller and its actions
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-26
 * @category    Controllers
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

use Phalcon_Tag as Tag;
use NDN_Session as Session;

class PlayersController extends NDN_Controller
{
    public function initialize()
    {
        Tag::setTitle('Manage Players');
        parent::initialize();

        $this->_bc->add('Players', 'players');

        $auth = Session::get('auth');
        $add  = '';

        if ($auth) {

            $add = Tag::linkTo(
                array(
                    'players/add',
                    'Add Player'
                )
            );
        }

        $this->view->setVar('addButton', $add);
        $this->view->setVar('top_menu', $this->_constructMenu($this));
    }

    public function indexAction()
    {

    }

    public function getAction()
    {
        $this->view->setRenderLevel(Phalcon_View::LEVEL_LAYOUT);
        $data = array();

        $players = Players::find();
        if (count($players) >= 0) {
            foreach ($players as $player) {
                $data[] = array(
                    'id'     => $player->id,
                    'name'   => $player->name,
                    'active' => ($player->active) ? 'Yes' : 'No',
                );
            }
        }

        echo json_encode(array('results' => $data));
    }

    public function addAction()
    {
        $auth = Session::get('auth');

        if ($auth) {

            if ($this->request->isPost()) {

                $player = new Players();
                $this->_setPlayer($player, $auth);

                if (!$player->save()) {
                    foreach ($player->getMessages() as $message) {
                        Session::setFlash(
                            'error',
                            (string) $message,
                            'alert alert-error'
                        );
                    }
                } else {
                    Session::setFlash(
                        'success',
                        'Player created successfully',
                        'alert alert-success'
                    );

                    $this->response->redirect('players/');
                }
            }
        }
    }

    public function editAction($id)
    {
        $auth = Session::get('auth');

        if ($auth) {

            $id     = $this->filter->sanitize($id, array('int'));
            $player = Players::findFirst('id=' . $id);

            if (!$player) {
                Session::setFlash(
                    'error',
                    'Player not found',
                    'alert alert-error'
                );

                $this->response->redirect('players/');
            }

            if ($this->request->isPost()) {

                $this->_setPlayer($player, $auth);

                if (!$player->save()) {
                    foreach ($player->getMessages() as $message) {
                        Session::setFlash(
                            'error',
                            (string) $message,
                            'alert alert-error'
                        );
                    }
                } else {
                    Session::setFlash(
                        'success',
                        'Player updated successfully',
                        'alert alert-success'
                    );

                    $this->response->redirect('players/');
                }

            }

            $this->view->setVar('id', $player->id);

            Tag::displayTo('id', $player->id);
            Tag::displayTo('name', $player->name);
            Tag::displayTo('active', $player->active);
        }
    }

    public function deleteAction($id)
    {
        $auth = Session::get('auth');

        if ($auth) {
            $id      = $this->filter->sanitize($id, array('int'));
            $player = Companies::findFirst('id=' . $id);
            if (!$player) {
                Session::setFlash(
                    'error',
                    'Player not found',
                    'alert alert-error'
                );

                $this->response->redirect('players/');
            }

            if (!$player->delete()) {
                foreach ($player->getMessages() as $message) {
                    Session::setFlash(
                        'error',
                        (string) $message,
                        'alert alert-error'
                    );
                }
                $this->response->redirect('players/');
            } else {
                Session::setFlash(
                    'success',
                    'Episode deleted successfully',
                    'alert alert-success'
                );

                $this->response->redirect('players/');
            }
        }
    }

    private function _setPlayer($player, $auth)
    {
        $datetime = date('Y-m-d H:i:s');

        $player->id     = $this->request->getPost('id', 'int');
        $player->name   = $this->request->getPost('name');
        $player->active = $this->request->getPost('active', 'int');

        $player->lastUpdate       = $datetime;
        $player->lastUpdateUserId = (int) $auth['id'];

        $player->name = strip_tags($player->name);
        $player->name = addslashes($player->name);
    }
}