<?php

/**
 * Elements
 *
 * Helps to build UI elements for the application
 */
abstract class Elements
{
    public static function getMenu($view)
    {
        $commonMenu = array(
                        'index'      => 'Home',
                        'awards'     => 'Awards',
                        'players'    => 'Players',
                        'episodes'   => 'Episodes',
                        'about'      => 'About',
                        'contact'    => 'Contact Us',
                      );

        $auth = Phalcon_Session::get('auth');

        $active         = $view->getControllerName();
        $sessionCaption = ($auth) ? 'Log Out'         : 'Log In';
        $sessionAction  = ($auth) ? '/session/logout' : '/session/index';

        $leftMenu = array();
        foreach ($commonMenu as $link => $text) {
            $leftMenu[] = array(
                'active' => (bool) ($active == $link),
                'link'   => '/' . $link,
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

        echo json_encode($menu);
    }

}
