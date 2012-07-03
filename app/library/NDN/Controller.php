<?php
/**
 * Controller.php
 * NDN_Controller
 *
 * The base controller and its actions
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
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

    /**
     * Hook called before dispatch
     */
    public function beforeDispatch()
    {
        $message = Session::getFlash();
        if (is_array($message)) {
            Flash::$message['class']($message['message'], $message['css']);
        }
        $this->view->setVar('breadcrumbs', $this->_bc->generate());
    }

    protected function _constructMenu($controller)
    {
        $commonMenu = array(
            'index'      => 'Home',
            'awards'     => 'Awards',
            'players'    => 'Players',
            'episodes'   => 'Episodes',
            'about'      => 'About',
            'contact'    => 'Contact Us',
        );

        $auth = Session::get('auth');

        $class          = get_class($controller);
        $class          = str_replace('Controller', '', $class);
        $active         = strtolower($class);
        $sessionCaption = ($auth) ? 'Log Out'         : 'Log In';
        $sessionAction  = ($auth) ? '/session/logout' : '/session/index';

        $leftMenu = array();
        foreach ($commonMenu as $link => $text) {
            $isActive   = (bool) ($active == $link);
            $newLink    = ('index' == $link) ? '/' : '/' . $link;
            $leftMenu[] = array(
                'active' => $isActive,
                'link'   => $newLink,
                'text'   => $text,
            );
        }

        $menu = new StdClass();
        $menu->current = $active;
        $menu->left    = $leftMenu;

        if ($auth != false) {
            $sessionCaption .= ' ' . $auth['name'];
        }

        $menu->rightLink = $sessionAction;
        $menu->rightText = $sessionCaption;

        return json_encode($menu);
    }
}
