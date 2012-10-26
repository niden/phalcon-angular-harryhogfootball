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

use \Phalcon\Tag as Tag;
use \Phalcon\Mvc\View as View;

class EpisodesController extends \NDN\Controller
{
    /**
     * initialization
     */
    public function initialize()
    {
        Tag::setTitle('Manage Episodes');
        parent::initialize();

        $this->_bc->add('Episodes', 'episodes');

        $auth = $this->session->get('auth');
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
        $this->view->setVar('menus', $this->constructMenu($this));
    }

    /**
     * index Action
     */
    public function indexAction()
    {

    }

    /**
     * get Action
     */
    public function getAction()
    {
        $this->view->setRenderLevel(View::LEVEL_LAYOUT);

        $data     = '';
        $results = $this->cache->get($this->getCacheHash('model'));

        if (!$results) {

            $episodes = Episodes::find();

            if (count($episodes) > 0) {
                foreach ($episodes as $episode) {
                    $data[] = array(
                        'id'      => $episode->id,
                        'number'  => $episode->number,
                        'airDate' => $episode->airDate,
                        'outcome' => $this->translateOutcome($episode->outcome),
                        'summary' => $episode->summary,
                    );
                }
            }

            $results = json_encode(array('results' => $data));

            $this->cache->save($this->getCacheHash('model'), $results);
        }

        echo $results;
    }

    /**
     * add Action
     */
    public function addAction()
    {
        $auth = Session::get('auth');

        if ($auth) {
            if ($this->request->isPost()) {

                $episode = new Episodes();
                $this->setEpisode($episode, $auth);

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

                    // Invalidate the cache
                    $cache  = Registry::get('cache');
                    $cache->remove($this->getCacheHash('model'));

                    $this->response->redirect('episodes/');
                }
            }
        }
    }

    /**
     * edit Action
     *
     * @param integer $id
     */
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

                $this->setEpisode($episode, $auth);

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

                    // Invalidate the cache
                    $cache  = Registry::get('cache');
                    $cache->remove($this->getCacheHash('model'));

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
            $episode = Episodes::findFirst('id=' . $id);
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

    private function translateOutcome($outcome)
    {
        switch ($outcome) {
            case -1:
                $result = 'L';
                break;
            case 0;
                $result = '-';
                break;
            case 1;
                $result = 'W';
                break;
        }

        return $result;
    }
    /**
     * Private helper setting episode fields
     *
     * @param $episode
     * @param $auth
     */
    private function setEpisode($episode, $auth)
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