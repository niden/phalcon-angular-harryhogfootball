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
                        'id'       => $episode->id,
                        'number'   => substr("0000" . $episode->number, -3),
                        'air_date' => $episode->air_date,
                        'outcome'  => $this->translateOutcome($episode->outcome),
                        'summary'  => $episode->summary,
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
        $auth = $this->session->get('auth');

        if ($auth) {
            if ($this->request->isPost()) {

                $this->view->disable();

                $episode = new Episodes();
                $this->setEpisode($episode, $auth);

                if (!$episode->save()) {
                    foreach ($episode->getMessages() as $message) {
                        $this->flash->error((string) $message);
                    }
                } else {
                    $this->flash->success('Episode created successfully');

                    // Invalidate the cache
                    $this->cache->delete($this->getCacheHash('model'));

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
        $auth = $this->session->get('auth');

        if ($auth) {

            $id      = $this->filter->sanitize($id, array('int'));
            $episode = Episodes::findFirst('id=' . $id);

            if (!$episode) {
                $this->flash->error('Episode not found');
                return $this->response->redirect('episodes/');
            }

            if ($this->request->isPost()) {

                $this->view->disable();

                $this->setEpisode($episode, $auth);

                if (!$episode->save()) {
                    foreach ($episode->getMessages() as $message) {
                        $this->flash((string) $message);
                    }
                } else {
                    $this->flash->success('Episode updated successfully');

                    // Invalidate the cache
                    $this->cache->delete($this->getCacheHash('model'));

                    $this->response->redirect('episodes/');
                }

            }

            $this->view->setVar('id', $episode->id);

            Tag::displayTo('id', $episode->id);
            Tag::displayTo('episode_id', $episode->number);
            Tag::displayTo('episode_date', $episode->air_date);
            Tag::displayTo('outcome', $episode->outcome);
            Tag::displayTo('summary', $episode->summary);
        }
    }

    public function deleteAction($id)
    {
        $auth = $this->session->get('auth');

        if ($auth) {
            $id      = $this->filter->sanitize($id, array('int'));
            $episode = Episodes::findFirst('id=' . $id);
            if (!$episode) {
                $this->flash->error('Episode not found');
                return $this->response->redirect('episodes/');
            }

            if (!$episode->delete()) {
                foreach ($episode->getMessages() as $message) {
                    $this->flash->error((string) $message);
                }

                return $this->response->redirect('episodes/');
            } else {
                $this->flash->success('Episode deleted successfully');
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
     */
    private function setEpisode($episode)
    {
        $episode->number   = $this->request->getPost('episode_id', 'int');
        $episode->air_date = $this->request->getPost('episode_date', 'int');
        $episode->summary  = $this->request->getPost('summary');
        $episode->outcome  = $this->request->getPost('outcome', 'int');
        $episode->summary  = strip_tags($episode->summary);
    }
}