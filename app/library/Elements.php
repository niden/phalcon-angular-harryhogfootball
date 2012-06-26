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

        $menu->right = array();

        if ($auth != false) {

            $menu->left[] = array(
                'active' => false,
                'link'   => '/awards/add',
                'text'   => 'Add Award',
            );

            $menu->right[] = array(
                                'active' => false,
                                'link'   => '/',
                                'text'   => 'Logged in as: ' . $auth['name'],
                             );
        }

        $menu->right[] = array(
                            'active' => false,
                            'link'   => $sessionAction,
                            'text'   => $sessionCaption,
                         );

        echo json_encode($menu);
    }

    public function getHof($limit = null)
    {
        $connection = Phalcon_Db_Pool::getConnection();
        $sql = 'SELECT COUNT(s.id) AS total, p.name AS playerName, s.award '
            . 'FROM scoring s '
            . 'INNER JOIN players p ON s.playerId = p.id '
            . 'GROUP BY s.award, s.playerId '
            . 'ORDER BY s.award ASC, total DESC, p.name ';

        $results = $connection->fetchAll($sql);

        $kicks = array();
        $gameb = array();
        $gcount = 0;
        $kcount = 0;
        $gmax   = 0;
        $kmax   = 0;
        foreach ($results as $item) {
            if ($item['award'] == 0) {
                if (!is_null($limit) && $limit > $kcount) {
                    $kmax = (0 == $kmax) ? $item['total'] : $kmax;
                    $kicks[] = array(
                                'total'   => $item['total'],
                                'name'    => $item['playerName'],
                                'percent' => (int) ($item['total'] * 100 / $kmax),
                               );
                    $kcount++;
                }
            } else {
                if (!is_null($limit) && $limit > $gcount) {
                    $gmax = (0 == $gmax) ? $item['total'] : $gmax;
                    $gameb[] = array(
                                'total'   => $item['total'],
                                'name'    => $item['playerName'],
                                'percent' => (int) ($item['total'] * 100 / $gmax),
                    );
                    $gcount++;
                }
            }
        }

        $result = array('gameballs' => $gameb, 'kicks' => $kicks);
        echo json_encode($result);
//        }

    }
}
