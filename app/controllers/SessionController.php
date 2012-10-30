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

use \Phalcon\Tag as Tag;

class SessionController extends \NDN\Controller
{
    /**
     * Initializes the controller
     */
    public function initialize()
    {
        Tag::setTitle('Log In');
        parent::initialize();
        $this->view->setVar('menus', $this->constructMenu($this));
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

                $this->registerSession($user);
                $this->flash->success('Welcome ' . $user->name);

                return $this->response->redirect('');
            }

            $this->flash->error('Wrong username/password combination');
        }

        return $this->response->redirect('');
    }

    /**
     * Finishes the active session redirecting to the index
     *
     * @return unknown
     */
    public function logoutAction()
    {
        $this->session->remove('auth');
        $this->flash->success('You are now logged out.');

        return $this->response->redirect('');
    }

    /**
     * Register authenticated user into session data
     *
     * @param Users $user
     */
    private function registerSession($user)
    {
        $this->session->set(
            'auth',
            array(
                'id'       => $user->id,
                'username' => $user->username,
                'name'     => $user->name,
            )
        );
    }

}
