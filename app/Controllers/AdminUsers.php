<?php


namespace Workspace\Controllers;


use Delight\Auth\EmailNotVerifiedException;
use Delight\Auth\InvalidPasswordException;
use Delight\Auth\TooManyRequestsException;
use Interop\Container\ContainerInterface;
use Workspace\Library\Controller;
use Workspace\Library\Helpers;
use Workspace\Models\AdminUsersModel;
use Delight\Auth\InvalidEmailException;

class AdminUsers extends Controller
{
    protected $m;
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->m = new AdminUsersModel($this->container);
    }

    public function indexAction($req,$res,$args){
        $data = $this->m->getUsers();
        if($data == false){
            $this->container->flash->addMessageNow('error','Failed to retrieve Admin users.');
        }
        $messages = $this->container->flash->getMessages();
        return $this->view->render($res, 'dashboard/adminusers.twig', compact('data', 'messages'));
    }

    public function loginPostAction($req,$res,$args){
            $params = $req->getParams();
            try {
                $this->container->auth->login($params['email'], $params['password']);
                $username=$this->container->auth->getUsername();
                $this->container->flash->addMessage('success','Welcome '. $username);
                return $res->withRedirect($this->container->router->pathFor('dashboard.index'));
            }
            catch (InvalidEmailException $e) {
                $this->container->flash->addMessage('error','Incorrect username or password 1');
            }
            catch (InvalidPasswordException $e) {
                $this->container->flash->addMessage('error','Incorrect username or password 2');
            }
            catch (EmailNotVerifiedException $e) {
                $this->container->flash->addMessage('error','Pending email verification');
            }
            catch (TooManyRequestsException $e) {
                $this->container->flash->addMessage('error','Too many authentication requests. Please wait a while');
            }
            return $res->withRedirect($this->container->router->pathFor('auth.loginRender'));
    }

    public function loginRenderAction($req,$res,$args){
        if($this->container->auth->isLoggedIn()){
            $username=$this->container->auth->getUsername();
            $this->container->flash->addMessage('success','Welcome '. $username . ' . You are already logged in.');
            return $res->withRedirect($this->container->router->pathFor('dashboard.index'));
        }
        else{
            $messages = $this->container->flash->getMessages();
            return $this->view->render($res, '/dashboard/login.twig', compact('messages'));
        }
    }

    public function logoutAction($req,$res,$args){
        try{
            $this->container->auth->logOut();
            $this->container->auth->destroySession();
            $this->container->flash->addMessageNow('success','Logout Successful');
        }
        catch(Exception $e){
            $this->container->flash->addMessage('error','Error during logout');
        }
        $messages = $this->container->flash->getMessages();
        return $res->withRedirect($this->container->router->pathFor('auth.login',[messages => $messages]));
    }

    public function addAction($req,$res,$args){
        $input_data = $req->getParams();
        $data = $this->m->addUser($input_data);
        if($data == false){
            $this->container->flash->addMessage('error','Failed to add Admin user.');
        }
        else{
            $this->container->flash->addMessage('success', 'Admin added successfully.');
        }
        return $res->withRedirect($this->container->router->pathFor('admins.index'));
    }

    public function deleteAction($req,$res,$args){
        $data = $this->m->deleteUser($args['id']);
        if($data == false){
            $this->container->flash->addMessage('error','Failed to remove Admin user.');
        }
        else{
            $this->container->flash->addMessage('success','Admin removed successfully');
        }
        return $res->withRedirect($this->container->router->pathFor('admins.index'));
    }

    public function resetAction($req,$res,$args){
        $dataParams = $req->getParams();
        $data = $this->m->setPassword($dataParams['userid'],$dataParams['password']);
        if($data == false){
            $this->container->flash->addMessage('error','Failed to reset password for ' . $dataParams['testing']);
        }
        else{
            $this->container->flash->addMessage('success','Reset password for ' . $dataParams['testing'] . ' successful.');
        }
        return $res->withredirect($this->container->router->pathFor('admins.index'));
    }

}