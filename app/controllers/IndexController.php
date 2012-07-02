<?php
/**
 * IndexController.php
 * IndexController
 *
 * The index controller and its actions
 *
 * @author      Nikos Dimopoulos <nikos@NDN.net>
 * @since       2012-06-21
 * @category    Controllers
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

class IndexController extends NDN_Controller
{
    public function initialize()
    {
        Phalcon_Tag::setTitle('Welcome');
        parent::initialize();
    }

    public function indexAction()
    {

    }
}
