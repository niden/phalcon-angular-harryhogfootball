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
use Phalcon_Flash as Flash;

class AwardsController extends ControllerBase
{
    /**
     * Initializes the controller
     */
    public function initialize()
    {
        $auth = Phalcon_Session::get('auth');

        // Not logged in user cannot come directly here
        if ($auth == false) {
            $this->_forward('/');
        }

        $this->view->setTemplateAfter('main');
        Tag::setTitle('Awards Entry');
        parent::initialize();

        $this->_bc->add('Awards', 'awards');
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
        $this->_bc->add('Add', 'awards/add');

        $players    = new Players();
        $allPlayers = $players->find();

        $this->view->setVar('players', $allPlayers);

        if (!$this->request->isPost()) {

        }
    }
}
