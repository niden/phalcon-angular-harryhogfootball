<?php
/**
 * ContactController.php
 * ContactController
 *
 * The contact controller and its actions
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-24
 * @category    Controllers
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

use \Phalcon\Flash as Flash;
use \Phalcon\Tag as Tag;

class ContactController extends \NDN\Controller
{
    /**
     * Initialization of the controller. Setting main template and title
     */
    public function initialize()
    {
        Tag::setTitle('Contact us');
        parent::initialize();

        $this->_bc->add('Contact Us', 'contact');
        $this->view->setVar('menus', $this->constructMenu($this));
    }

    /**
     * Controls the index action
     */
    public function indexAction()
    {
    }

    /**
     * Handles the sending the message - storing it in the database
     *
     * @todo Refactor this to send an email
     */
    public function sendAction()
    {
        if ($this->request->isPost() == true) {

            $forward = 'index/index';

            $name     = $this->request->getPost('name', 'string');
            $email    = $this->request->getPost('email', 'email');
            $comments = $this->request->getPost('comments', 'string');

            $name     = strip_tags($name);
            $comments = strip_tags($comments);

            $contact            = new Contact();
            $contact->name      = $name;
            $contact->email     = $email;
            $contact->comments  = $comments;
            $contact->createdAt = new Phalcon_Db_RawValue('now()');

            if ($contact->save() == false) {
                foreach ($contact->getMessages() as $message) {
                    Flash::error((string) $message, 'alert alert-error');
                }

                $forward = 'contact/index';
            } else {
                $message = 'Thank you for your input. If your message requires '
                         . 'a reply, we will contact you as soon as possible.';
                Flash::success($message, 'alert alert-success');
            }
        } else {
            $forward = 'contact/index';
        }

        return $this->_forward($forward);
    }
}
