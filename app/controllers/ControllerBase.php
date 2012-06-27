<?php
/**
 * ControllerBase.php
 * ControllerBase
 *
 * The base controller and its actions
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-22
 * @category    Controllers
 * @license     MIT - https://github.com/niden/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

use Phalcon_Flash as Flash;
use niden_Session as Session;

class ControllerBase extends Phalcon_Controller
{
    protected $_bc = null;

    /**
     * Initializes the controller
     */
    public function initialize()
    {
        Phalcon_Tag::prependTitle('HHF G&KB Awards | ');

        $this->_bc = new niden_Breadcrumbs();
    }

    public function beforeDispatch()
    {
        $message = Session::getFlash();
        if (is_array($message)) {
            Flash::$message['class']($message['message'], $message['css']);
        }
        $this->view->setVar('breadcrumbs', $this->_bc->generate());
    }
}
