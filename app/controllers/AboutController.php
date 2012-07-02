<?php
/**
 * AboutController.php
 * AboutController
 *
 * The about controller and its actions
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-24
 * @category    Controllers
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

use Phalcon_Tag as Tag;

class AboutController extends NDN_Controller
{
    public function initialize()
    {
        Tag::setTitle('About');
        parent::initialize();

        $this->_bc->add('About', 'about');
    }

    public function indexAction()
    {
    }
}
