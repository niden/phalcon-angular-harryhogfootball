<?php
/**
 * IndexController.php
 * IndexController
 *
 * The index controller and its actions
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-21
 * @category    Controllers
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

use \Phalcon\Tag as Tag;

class IndexController extends \NDN\Controller
{
    public function initialize()
    {
        Tag::setTitle('Welcome');
        parent::initialize();
        $this->view->setVar('top_menu', $this->constructMenu($this));
    }

    /**
     * index Action
     */
    public function indexAction()
    {
    }
}
