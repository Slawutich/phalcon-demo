<?php
declare(strict_types=1);

use Phalcon\Validation;
use Phalcon\Validation\Validator\Confirmation;

class SignupController extends ControllerBase
{

    public function indexAction()
    {

    }

    private function validate(array $data){
        $validation = new Validation();

        $validation->add(
            "password",
            new Confirmation(
                [
                    "message" => "Password doesn't match confirmation",
                    "with"    => "confirm-password",
                ]
            )
        );

        return $validation->validate($data);
    }

    public function registerAction()
    {
        $data = $this->request->getPost();
        $data['active'] = 1;

        $messages = $this->validate($data);

        if(count($messages)>0){
            $success = false;
            $this->view->registerErrors = $messages;
        }else{
            $user = new Users();
            $user->assign(
                $data,
                [
                    'name',
                    'email',
                    'password',
                    'active'
                ]
            );

            $success = $user->save();
            $messages = $user->getMessages();
        }

        $this->view->success = $success;
        $this->view->registerErrors = $messages;
    }
}

