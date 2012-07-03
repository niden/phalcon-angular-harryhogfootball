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
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

use Phalcon_Tag as Tag;
use NDN_Session as Session;

class SessionController extends NDN_Controller
{
    /**
     * Initializes the controller
     */
    public function initialize()
    {
        Tag::setTitle('Log In');
        parent::initialize();
        $this->view->setVar('top_menu', $this->_constructMenu($this));
    }

    /**
     * The index action
     */
    public function indexAction()
    {
        if (!$this->request->isPost()) {
            Tag::setDefault('username', 'user@email.com');
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

            $username = $this->request->getPost('username', 'email');
            $password = $this->request->getPost('password');

            $password = sha1($password);

            $conditions = 'username = :username: AND password = :password:';
            $parameters = array(
                            'username' => $username,
                            'password' => $password,
                          );
            $user = Users::findFirst(array($conditions, 'bind' => $parameters));

            if ($user != false) {

                $this->_registerSession($user);
                Session::setFlash(
                    'success',
                    'Welcome ' . $user->name,
                    'alert alert-success'
                );

                return $this->response->redirect('/');
            }

            Session::setFlash(
                'error',
                'Wrong username/password combination',
                'alert alert-error'
            );
        }

        return $this->response->redirect('session');
    }

    /**
     * Finishes the active session redirecting to the index
     *
     * @return unknown
     */
    public function logoutAction()
    {
        Session::remove('auth');

        Session::setFlash(
            'success',
            'You are now logged out.',
            'alert alert-success'
        );

        return $this->response->redirect('/');
    }

    /**
     * Register authenticated user into session data
     *
     * @param Users $user
     */
    private function _registerSession($user)
    {
        Session::set(
            'auth',
            array(
                'id'       => $user->id,
                'username' => $user->username,
                'name'     => $user->name,
            )
        );
    }

}
