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
use Phalcon_Flash as Flash;

class PlayersController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Tag::setTitle('Manage Players');
        parent::initialize();

        $this->_bc->add('Players', 'Players');
    }

    public function indexAction()
    {

    }

    public function getAction()
    {
        $this->view->setRenderLevel(Phalcon_View::LEVEL_LAYOUT);
        $parameters['order'] = 'active DESC, name';
        $data = array();

        $players = Players::find($parameters);
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
        if ($this->request->isPost()) {

            $player = new Players();
            $this->_setEpisode($player);

            if (!$player->save()) {
                foreach ($player->getMessages() as $message) {
                    Flash::error((string) $message, 'alert alert-error');
                }
            } else {
                Flash::success(
                    'Player created successfully',
                    'alert alert-success'
                );
                $this->_forward('/Players/add');
            }
        }
    }

    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $id      = $this->filter->sanitize($id, array('int'));
            $player = Players::findFirst('id=' . $id);

            if (!$player) {
                Flash::error('Episode not found', 'alert alert-error');

                return $this->_forward('Players');
            }

            $this->view->setVar('id', $player->id);

            Tag::displayTo('id', $player->id);
            Tag::displayTo('number', $player->number);
            Tag::displayTo('airDate', $player->airDate);
            Tag::displayTo('outcome', $player->outcome);
            Tag::displayTo('summary', $player->summary);
        }
    }

    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->_forward('Players');
        }

        $id      = $this->request->getPost('id', 'int');
        $player = Players::findFirst('id=' . $id);

        if (!$player) {
            Flash::error('Episode does not exist ' . $id, 'alert alert-error');

            return $this->_forward('Players');
        }

        $this->_setEpisode($player);

        if (!$player->save()) {
            foreach ($player->getMessages() as $message) {
                Flash::error((string) $message, 'alert alert-error');
            }

            return $this->_forward('Players/edit/' . $player->id);
        } else {
            Flash::success('Episode updated successfully', 'alert alert-success');

            return $this->_forward('Players');
        }
    }

    public function deleteAction($id)
    {
        $id      = $this->filter->sanitize($id, array('int'));
        $player = Companies::findFirst('id=' . $id);
        if (!$player) {
            Flash::error('Episode not found', 'alert alert-error');

            return $this->_forward('Players');
        }

        if (!$player->delete()) {
            foreach ($player->getMessages() as $message) {
                Flash::error((string) $message, 'alert alert-error');
            }

            return $this->_forward('companies/search');
        } else {
            Flash::success('Episode deleted', 'alert alert-success');

            return $this->_forward('Players');
        }
    }

    private function _setEpisode($player)
    {
        $player->name   = $this->request->getPost('name');
        $player->active = $this->request->getPost('active', 'int');
        $player->team   = $this->request->getPost('team', 'int');

        $player->name = strip_tags($player->name);
        $player->team = strip_tags($player->team);

    }
}