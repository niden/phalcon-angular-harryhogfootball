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
 * @license     MIT - https://github.com/niden/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

class IndexController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Phalcon_Tag::setTitle('Welcome');
        parent::initialize();
    }

    public function indexAction()
    {
        if (!$this->request->isPost()) {

            $message = 'This application showcases the power of Phalcon PHP '
                     . 'Framework as well as AngularJS. It also serves as a '
                     . 'quick search and statistics generator for the Game '
                     . 'Balls and Kick In The Balls awards of the Harry Hog '
                     . 'Football podcast';

            Phalcon_Flash::notice($message, 'alert alert-info');
        }
    }
}
