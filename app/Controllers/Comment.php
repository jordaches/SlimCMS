<?php


namespace Workspace\Controllers;


use Interop\Container\ContainerInterface;
use Workspace\Library\Controller;
use Workspace\Library\Helpers;
use Workspace\Models\CommentModel;

class Comment extends Controller
{
    protected $m;
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->m = new CommentModel($this->container);
    }

    public function indexAction($req,$res,$args){
        $data = $this->m->getComments();
        if($data == false){
            $this->container->flash->addMessageNow('error','Failed to obtain list of comments.');
        }
        $approved = [];
        $disapproved = [];
        foreach ($data as $d){
            ($d['status'] == "ON") ? $approved[] = $d : $disapproved[] = $d;
        }
        $messages = $this->container->flash->getMessages();
        $this->container->view->render($res, 'dashboard/comments.twig', compact('approved','disapproved','messages'));
    }

    public function approveAction($req,$res,$args){
        $data = $this->m->approveComment($args['id']);
        if($data == false){
            $this->container->flash->addMessage('error','Failed to approve comment.');
        }
        else{
            $this->container->flash->addMessage('success','Comment approved successfully.');
        }
        return $res->withRedirect($this->container->router->pathFor('comment.index'));
    }

    public function disapproveAction($req,$res,$args){
        $data = $this->m->disapproveComment($args['id']);
        if($data == false){
            $this->container->flash->addMessage('error','Failed to disapprove comment.');
        }
        else{
            $this->container->flash->addMessage('success','Comment disapproved successfully.');
        }
        return $res->withRedirect($this->container->router->pathFor('comment.index'));
    }

    public function deleteAction($req,$res,$args){
        $data = $this->m->deleteComment($args['id']);
        if($data == false){
            $this->container->flash->addMessage('error','Failed to delete comment.');
        }
        else{
            $this->container->flash->addMessage('success','Comment deleted successfully.');
        }
        return $res->withRedirect($this->container->router->pathFor('comment.index'));
    }
}