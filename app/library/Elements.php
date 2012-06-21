<?php

/**
 * Elements
 *
 * Helps to build UI elements for the application
 */
abstract class Elements
{
    private static $_headerMenu = array(
        'pull-left' => array(
            'index' => array(
                'caption' => 'Home',
                'action'  => ''
            ),
            'statistics' => array(
                'caption' => 'Statistics',
                'action'  => ''
            ),
            'about' => array(
                'caption' => 'About',
                'action'  => ''
            ),
            'contact' => array(
                'caption' => 'Contact',
                'action'  => ''
            ),
        ),
        'pull-right' => array(
            'session' => array(
                'caption' => 'Log In',
                'action'  => ''
            ),
        )
    );

    private static $_tabs = array(
        'Invoices' => array(
            'controller' => 'invoices',
            'action' => 'index',
            'any' => false
        ),
        'Companies' => array(
            'controller' => 'companies',
            'action' => 'index',
            'any' => true
        ),
        'Products' => array(
            'controller' => 'products',
            'action' => 'index',
            'any' => true
        ),
        'Product Types' => array(
            'controller' => 'producttypes',
            'action' => 'index',
            'any' => true
        ),
        'Your Profile' => array(
            'controller' => 'invoices',
            'action' => 'profile',
            'any' => false
        )
    );

    public static function getMenu($view)
    {
        $auth = Phalcon_Session::get('auth');

        $active         = $view->getControllerName();
        $sessionCaption = ($auth) ? 'Log In'        : 'Log Out';
        $sessionAction  = ($auth) ? 'session/login' : 'session/logout';

        $menu = new StdClass();

        $menu->left = array(
            array(
                    'active' => (bool) ($active == 'index'),
                    'link'   => Phalcon_Tag::linkTo('index', 'Home'),
                ),
                array(
                    'active' => (bool) ($active == 'statistics'),
                    'link' => Phalcon_Tag::linkTo('statistics', 'Statistics'),
                ),
                array(
                    'active' => (bool) ($active == 'about'),
                    'link' => Phalcon_Tag::linkTo('about', 'About'),
                ),
                array(
                    'active' => (bool) ($active == 'contact'),
                    'link' => Phalcon_Tag::linkTo('contact', 'Contact Us'),
                ),
            );

        $menu->right = array(
                array(
                    'active' => false,
                    'link' => Phalcon_Tag::linkTo(
                        $sessionAction, $sessionCaption
                    ),
                ),
            );

        echo json_encode($menu);
    }

    /**
     * Builds header menu with left and right items
     *
     * @param  Phalcon_View $view
     * @return string
     */
    public static function getMenu1($view)
    {
        $auth = Phalcon_Session::get('auth');
        if ($auth) {
            self::$_headerMenu['pull-right']['session'] = array(
                'caption' => 'Log Out',
                'action'  => 'end'
            );
        } else {
            unset(self::$_headerMenu['pull-left']['invoices']);
        }

        echo '<div class="nav-collapse">';
        $controllerName = $view->getControllerName();
        foreach (self::$_headerMenu as $position => $menu) {
            echo '<ul class="nav ', $position, '">';
            foreach ($menu as $controller => $option) {
                if ($controllerName == $controller) {
                    echo '<li class="active">';
                } else {
                    echo '<li>';
                }
                echo Phalcon_Tag::linkTo($controller.'/'.$option['action'], $option['caption']);
                echo '</li>';
            }
            echo '</ul>';
        }
        echo '</div>';
    }

    public static function getTabs($view)
    {
        $controllerName = $view->getControllerName();
        $actionName = $view->getActionName();
        echo '<ul class="nav nav-tabs">';
        foreach (self::$_tabs as $caption => $option) {
            if ($option['controller'] == $controllerName && ($option['action'] == $actionName || $option['any'])) {
                echo '<li class="active">';
            } else {
                echo '<li>';
            }
            echo Phalcon_Tag::linkTo($option['controller'].'/'.$option['action'], $caption), '<li>';
        }
        echo '</ul>';
    }
}
