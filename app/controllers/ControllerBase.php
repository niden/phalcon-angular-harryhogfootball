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

class ControllerBase extends Phalcon_Controller
{
    /**
     * Initializes the controller
     */
    public function initialize()
    {
        Phalcon_Tag::prependTitle('HHF G&KB Awards | ');
    }
}
