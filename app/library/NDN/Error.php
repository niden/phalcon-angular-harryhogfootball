<?php
/**
 * Error.php
 * Error
 *
 * Handles error displaying and logging
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       7/22/12
 * @category    Library
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

namespace NDN;

class Error
{
    public static function normal($type, $message, $file, $line)
    {
        $error = array(
            'type'    => $type,
            'message' => $message,
            'file'    => $file,
            'line'    => $line,
        );

        // Log it

        // Display it under regular circumstances
    }

    public static function exception($exception)
    {
        // Log the error

        // Display it
    }

    public static function shutdown()
    {
        $error = error_get_last();
        if ($error) {

            // Log it

            // Display it

        } else {
            return true;
        }
    }
}
