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
 * @license     MIT - https://github.com/niden/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */
use Phalcon_Tag as Tag;
use niden_Session as Session;

class PlayersController extends ControllerBase
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
                    'Add Player',
                    'class' => 'btn btn-primary'
                )
            );
        }

        $this->view->setVar('addButton', $add);
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
                    'team'   => $player->team,
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
                $this->_setPlayer($player);

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

                $this->_setPlayer($player);

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
            Tag::displayTo('team', $player->team);
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

    private function _setPlayer($player)
    {
        $player->id     = $this->request->getPost('id', 'int');
        $player->name   = $this->request->getPost('name');
        $player->active = $this->request->getPost('active', 'int');
        $player->team   = $this->request->getPost('team');

        $player->name = strip_tags($player->name);
        $player->team = strip_tags($player->team);

        $player->name = addslashes($player->name);
        $player->team = addslashes($player->team);
    }
}