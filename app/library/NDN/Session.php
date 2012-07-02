<?php
/**
 * Session.php
 * niden_Session
 *
 * Session pattern implementation
 *
 * @author      Nikos Dimopoulos <nikos@NDN.net>
 * @since       6/24/12
 * @category    Library
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

class NDN_Session extends Phalcon_Session
{
    public static function setFlash($class, $message, $css)
    {
        $data = array(
            'class'   => $class,
            'message' => $message,
            'css'     => $css,
        );
        self::set('flash', $data);
    }

    public static function getFlash()
    {
        $data = self::get('flash');

        if (is_array($data)) {
            self::remove('flash');
            return $data;
        } else {
            return null;
        }
    }
}