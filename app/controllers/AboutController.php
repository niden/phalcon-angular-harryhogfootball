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

use \Phalcon\Tag as Tag;

class AboutController extends \NDN\Controller
{
    public function initialize()
    {
        Tag::setTitle('About');
        parent::initialize();

        $this->_bc->add('About', 'about');
        $this->view->setVar('top_menu', $this->constructMenu($this));
    }

    public function indexAction()
    {
    }
}
