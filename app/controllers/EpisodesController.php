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
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

use Phalcon_Tag as Tag;
use NDN_Session as Session;

class EpisodesController extends NDN_Controller
{
    public function initialize()
    {
        Tag::setTitle('Manage Episodes');
        parent::initialize();

        $this->_bc->add('Episodes', 'episodes');

        $auth = Session::get('auth');
        $add  = '';

        if ($auth) {

            $add = Tag::linkTo(
                array(
                    'episodes/add',
                    'Add Episode'
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

        $data     = array();
        $episodes = Episodes::find();

        if (count($episodes) > 0) {
            foreach ($episodes as $episode) {
                $data[] = array(
                            'id'      => $episode->id,
                            'number'  => $episode->number,
                            'airDate' => $episode->airDate,
                            'outcome' => ($episode->outcome == 1) ? 'W' : 'L',
                            'summary' => $episode->summary,
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

                $episode = new Episodes();
                $this->_setEpisode($episode, $auth);

                if (!$episode->save()) {
                    foreach ($episode->getMessages() as $message) {
                        Session::setFlash(
                            'error',
                            (string) $message,
                            'alert alert-error'
                        );
                    }
                } else {
                    Session::setFlash(
                        'success',
                        'Episode created successfully',
                        'alert alert-success'
                    );

                    $this->response->redirect('episodes/');
                }
            }
        }
    }

    public function editAction($id)
    {
        $auth = Session::get('auth');

        if ($auth) {

            $id      = $this->filter->sanitize($id, array('int'));
            $episode = Episodes::findFirst('id=' . $id);

            if (!$episode) {
                Session::setFlash(
                    'error',
                    'Episode not found',
                    'alert alert-error'
                );

                return $this->response->redirect('episodes/');
            }

            if ($this->request->isPost()) {

                $this->_setEpisode($episode, $auth);

                if (!$episode->save()) {
                    foreach ($episode->getMessages() as $message) {
                        Session::setFlash(
                            'error',
                            (string) $message,
                            'alert alert-error'
                        );
                    }
                } else {
                    Session::setFlash(
                        'success',
                        'Episode updated successfully',
                        'alert alert-success'
                    );

                    $this->response->redirect('episodes/');
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
        $auth = Session::get('auth');

        if ($auth) {
            $id      = $this->filter->sanitize($id, array('int'));
            $episode = Companies::findFirst('id=' . $id);
            if (!$episode) {
                Session::setFlash(
                    'error',
                    'Episode not found',
                    'alert alert-error'
                );

                return $this->response->redirect('episodes/');
            }

            if (!$episode->delete()) {
                foreach ($episode->getMessages() as $message) {
                    Session::setFlash(
                        'error',
                        (string) $message,
                        'alert alert-error'
                    );
                }

                return $this->response->redirect('episodes/');
            } else {
                Session::setFlash(
                    'success',
                    'Episode deleted successfully',
                    'alert alert-success'
                );

                return $this->response->redirect('episodes/');
            }
        }
    }

    /**
     * Private helper setting episode fields
     *
     * @param $episode
     * @param $auth
     */
    private function _setEpisode($episode, $auth)
    {
        $datetime = date('Y-m-d H:i:s');

        $episode->id      = $this->request->getPost('episodeId', 'int');
        $episode->number  = $this->request->getPost('episodeId', 'int');
        $episode->airDate = $this->request->getPost('episodeDate', 'int');
        $episode->summary = $this->request->getPost('summary');
        $episode->outcome = $this->request->getPost('outcome', 'int');

        $episode->lastUpdate       = $datetime;
        $episode->lastUpdateUserId = (int) $auth['id'];

        $episode->summary = strip_tags($episode->summary);
        $episode->summary = addslashes($episode->summary);

    }
}