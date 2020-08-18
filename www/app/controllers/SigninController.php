<?php
declare(strict_types=1);

use Phalcon\Http\Response;

class SigninController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {

    }

    public function logoutAction()
    {
        $this->session->remove('userId');
        $this->user = null;
        $response = new Response();
        $response->redirect('/');
        return $response;
    }

    public function loginAction()
    {
        if($this->user === null){
            $email    = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $user = Users::findFirst(
                [
                    'conditions' => 'email = :email:',
                    'bind'       => [
                        'email' => $email,
                    ],
                ]
            );

            if ($user !== false) {
                $check = $this->security->checkHash($password, $user->password);

                if ($check === true) {
                    $this->session->set('userId', $user->id);
                    $response = new Response();
                    $response->redirect('/');
                    return $response;
                }else{
                    $this->view->loginErrors = ["Email or password not valid"];
                }
            } else {
                $this->view->loginErrors = ["Email or password not valid"];
            }
        }else{
            $response = new Response();
            $response->redirect('/');
            return $response;
        }
    }
}

