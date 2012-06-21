<?php
/**
 * SessionController.php
 * SessionController
 *
 * The session/login controller and its actions
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-21
 * @category    Controllers
 * @license     MIT - https://github.com/niden/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

use Phalcon_Tag as Tag;
use Phalcon_Flash as Flash;

class SessionController extends ControllerBase
{
    /**
     * Initializes the controller
     */
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Tag::setTitle('Log In');
        parent::initialize();
    }

    /**
     * The index action
     */
    public function indexAction()
    {
        if (!$this->request->isPost()) {
            Tag::setDefault('email', 'user@email.com');
            Tag::setDefault('password', 'hhf');
        }
    }

    /**
     * This actions receives the input from the login form
     *
     */
    public function loginAction()
    {
        if ($this->request->isPost()) {

            $email    = $this->request->getPost('email', 'email');
            $password = $this->request->getPost('password');

            $password = sha1($password);

            $user = Users::findFirst(
                "username='$email' AND password='$password'"
            );

            if ($user != false) {

                $this->_registerSession($user);
                Flash::success(
                    'Welcome ' . $user->username,
                    'alert alert-success'
                );

                return $this->_forward('/index');
            }

            Flash::error('Wrong email/password', 'alert alert-error');
        }

        return $this->_forward('/session');
    }

    /**
     * Finishes the active session redirecting to the index
     *
     * @return unknown
     */
    public function logoutAction()
    {
        unset($_SESSION['auth']);
        Flash::success('Goodbye!', 'alert alert-success');

        return $this->_forward('/index');
    }

    /**
     * Register authenticated user into session data
     *
     * @param Users $user
     */
    private function _registerSession($user)
    {
        Phalcon_Session::set(
            'auth',
            array(
                'id'   => $user->id,
                'name' => $user->username,
            )
        );
    }

}
