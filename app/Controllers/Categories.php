<?php


namespace Workspace\Controllers;


use Interop\Container\ContainerInterface;
use Workspace\Library\Controller;
use Workspace\Library\Helpers;
use Workspace\Models\CategoriesModel;

class Categories extends Controller
{
    protected $m;
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->m = new CategoriesModel($this->container);
    }

    public function indexAction($req,$res,$args){
        $data = $this->m->getCategories();
        if($data == false){
            $this->container->flash->addMessageNow('error','Failed to retrieve Categories.');
        }
        $messages = $this->container->flash->getMessages();
        return $this->view->render($res, 'dashboard/categories.twig', compact('data', 'messages'));
    }

    public function addAction($req,$res,$args){
        $category = $req->getParams();
        $data = $this->m->addCategory($category['Category']);
        if($data == false){
            $this->container->flash->addMessage('error','Failed to add category.');
        }
        else{
            $this->container->flash->addMessage('success','Category added successfully.');
        }
        return $res->withRedirect($this->container->router->pathFor('categories.index'));
    }

    public function deleteAction($req,$res,$args){
        $data = $this->m->deleteCatergory($args['id']);
        if($data == false){
            $this->container->flash->addMessage('error','Failed to delete category.');
        }
        else{
            $this->container->flash->addMessage('success','Category deleted successfully.');
        }
        return $res->withRedirect($this->container->router->pathFor('categories.index'));
    }
}