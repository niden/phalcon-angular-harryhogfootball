<?php
/**
 * Controller.php
 * NDN_Controller
 *
 * The base controller and its actions
 *
 * @author      Nikos Dimopoulos <nikos@NDN.net>
 * @since       2012-07-01
 * @category    Controllers
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

use Phalcon_Tag as Tag;
use Phalcon_Flash as Flash;
use NDN_Session as Session;

class NDN_Controller extends Phalcon_Controller
{
    protected $_bc = null;

    /**
     * Initializes the controller
     */
    public function initialize()
    {
        Tag::prependTitle('HHF G&KB Awards | ');

        $this->_bc = new NDN_Breadcrumbs();
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
