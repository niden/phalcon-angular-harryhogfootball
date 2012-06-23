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
            'statistics' => 'Statistics',
            'about'      => 'About',
            'contact'    => 'Contact Us',
        );

        $auth = Phalcon_Session::get('auth');

        $active         = $view->getControllerName();
        $sessionCaption = ($auth) ? 'Log In'         : 'Log Out';
        $sessionAction  = ($auth) ? '/session/login' : '/session/logout';

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
        $menu->right   = array(
            array(
                'active' => false,
                'link'   => $sessionAction,
                'text'   => $sessionCaption,
            ),
        );

        echo json_encode($menu);
    }
}
