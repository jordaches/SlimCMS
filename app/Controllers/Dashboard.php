<?php


namespace Workspace\Controllers;


use Interop\Container\ContainerInterface;
use Workspace\Library\Controller;
use Workspace\Library\Helpers;
use Workspace\Models\CategoriesModel;
use Workspace\Models\DashboardModel;

class Dashboard extends Controller
{
    protected $m;
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->m = new DashboardModel($this->container);
    }

    public function indexAction($req, $res, $args)
    {
        $data = $this->m->getPosts();
        if($data == false){
            $this->container->flash->addMessageNow('error','Failed to obtain list of posts.');
        }
        $messages = $this->container->flash->getMessages();
        return $this->view->render($res, 'dashboard/index.twig', compact('data','messages'));
    }

    public function addAction($req, $res, $args){
        if($req->isPost()){
            $params = $req->getParams();
            $directory = $this->container['upload_directory'];
            $uploadedFiles = $req->getUploadedFiles();
            $uploadedFile = array_shift($uploadedFiles);
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $filename = Helpers::moveUploadedFile($directory, $uploadedFile);
                $params['imageName'] = $filename;
            }
            else{
                $params['imageName'] = null;
            }
            $data = $this->m->addPost($params);
            if($data == false){
                $this->container->flash->addMessage('error','Failed to add post.');
            }
            else{
                $this->container->flash->addMessage('success','Post added successfully.');
            }
            return $res->withRedirect($this->container->router->pathFor('dashboard.index'));
        }
        else {
            $m = new CategoriesModel($this->container);
            $data = $m->getCategories();
            return $this->view->render($res, 'dashboard/add.twig', compact('data'));
        }
    }

    public function editAction($req, $res, $args){
        $model = new CategoriesModel($this->container);
        $categories = $model->getCategories();
        $data = array_shift($this->m->getPost($args['id']));
        //Helpers::DebugDie($data);
        $this->view->render($res, 'dashboard/editpost.twig', compact('data','categories'));
    }

    public function deleteAction($req, $res, $args)
    {
        $data = $this->m->deletePost($args['id']);
        if($data == false){
            $this->container->flash->addMessage('error','Failed to delete post.');
        }
        else{
            $this->container->flash->addMessage('success','Post deleted successfully.');
        }
        return $res->withRedirect($this->container->router->pathFor('dashboard.index'));
    }

    public function previewAction($req, $res, $args){

            //Implemented from Blog View
    }

    public function updateAction($req, $res, $args){
        $directory = $this->container['upload_directory'];
        $uploadedFiles = $req->getUploadedFiles();
        $uploadedFile = array_shift($uploadedFiles);
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = Helpers::moveUploadedFile($directory, $uploadedFile);
        }
        else{
            $filename = null;
        }
        $data = $req->getParams();
        $data['image'] = $filename;
        $id = $args['id'];
        $data = $this->m->updatePost($id,$data);
        if($data){
            $this->container->flash->addMessage('success','Post edited successfully.');
        }
        else{
            $this->container->flash->addMessage('error','Failed to edit post.');
        }
        return $res->withRedirect($this->container->router->pathFor('dashboard.index'));
    }
}