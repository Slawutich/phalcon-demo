<?php
declare(strict_types=1);

use Phalcon\Http\Response;

class UsersController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        if($this->acl->isAllowed('Users', 'index')){
            $this->view->users = Users::find();
        }else{
            $response = new Response();
            $response->redirect('signin');
            return $response;
        }
    }

}

