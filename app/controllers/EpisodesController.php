<?php
/**
 * EpisodesController.php
 * EpisodesController
 *
 * The episodes controller and its actions
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-26
 * @category    Controllers
 * @license     MIT - https://github.com/niden/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */
use Phalcon_Tag as Tag;
use Phalcon_Flash as Flash;

class EpisodesController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Tag::setTitle('Manage Episodes');
        parent::initialize();

        $this->_bc->add('Episodes', 'episodes');

        $auth = Phalcon_Session::get('auth');
        $add  = '';

        if ($auth) {

            $add = Tag::linkTo(
                array(
                    'episodes/add',
                    'Add Episode',
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

        $episodes = Episodes::find();
        if (count($episodes) == 0) {
            Flash::notice('No episodes in the database', 'alert alert-info');

            return $this->_forward('episodes');
        }

        $data = array();
        foreach ($episodes as $episode) {
            $data[] = array(
                        'id'      => $episode->id,
                        'number'  => $episode->number,
                        'airDate' => $episode->airDate,
                        'outcome' => ($episode->outcome) ? 'W' : 'L',
                        'summary' => $episode->summary,
                      );
        }

        echo json_encode(array('results' => $data));
    }

    public function addAction()
    {
        $auth = Phalcon_Session::get('auth');

        if ($auth) {
            if ($this->request->isPost()) {

                $episode = new Episodes();
                $this->_setEpisode($episode);

                if (!$episode->save()) {
                    foreach ($episode->getMessages() as $message) {
                        Flash::error((string) $message, 'alert alert-error');
                    }
                } else {
                    Flash::success(
                        'Episode created successfully',
                        'alert alert-success'
                    );
                    $this->_forward('episodes/');
                }
            }
        }
    }

    public function editAction($id)
    {
        $auth = Phalcon_Session::get('auth');

        if ($auth) {

            $id      = $this->filter->sanitize($id, array('int'));
            $episode = Episodes::findFirst('id=' . $id);

            if (!$episode) {
                Flash::error('Episode not found', 'alert alert-error');

                return $this->_forward('episodes/');
            }

            if ($this->request->isPost()) {

                $this->_setEpisode($episode);

                if (!$episode->save()) {
                    foreach ($episode->getMessages() as $message) {
                        Flash::error((string) $message, 'alert alert-error');
                    }
                } else {
                    Flash::success(
                        'Episode updated successfully',
                        'alert alert-success'
                    );
                    $this->_forward('episodes/');
                }

            }

            $this->view->setVar('id', $episode->id);

            Tag::displayTo('id', $episode->id);
            Tag::displayTo('episodeId', $episode->number);
            Tag::displayTo('episodeDate', $episode->airDate);
            Tag::displayTo('outcome', $episode->outcome);
            Tag::displayTo('summary', $episode->summary);
        }
    }

    public function deleteAction($id)
    {
        $auth = Phalcon_Session::get('auth');

        if ($auth) {
            $id      = $this->filter->sanitize($id, array('int'));
            $episode = Companies::findFirst('id=' . $id);
            if (!$episode) {
                Flash::error('Episode not found', 'alert alert-error');

                return $this->_forward('episodes/');
            }

            if (!$episode->delete()) {
                foreach ($episode->getMessages() as $message) {
                    Flash::error((string) $message, 'alert alert-error');
                }

                return $this->_forward('companies/search');
            } else {
                Flash::success('Episode deleted', 'alert alert-success');

                return $this->_forward('episodes/');
            }
        }
    }

    private function _setEpisode($episode)
    {
        $episode->id      = $this->request->getPost('episodeId', 'int');
        $episode->number  = $this->request->getPost('episodeId', 'int');
        $episode->airDate = $this->request->getPost('episodeDate', 'int');
        $episode->summary = $this->request->getPost('summary');
        $episode->outcome = $this->request->getPost('outcome', 'int');

        $episode->summary = strip_tags($episode->summary);
        $episode->summary = addslashes($episode->summary);

    }
}