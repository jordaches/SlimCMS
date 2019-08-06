<?php


namespace Workspace\Controllers;


use Interop\Container\ContainerInterface;
use Workspace\Library\Controller;
use Workspace\Library\Helpers;
use Workspace\Models\BlogModel;
use Workspace\Models\PaginationModel;

class Blog extends Controller
{
    protected $m;
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->m = new BlogModel($this->container);
    }

    public function indexAction($req, $res, $args)
    {
        $postsPerPage = 5;
        $paginate = new PaginationModel($this->container);
        $pages = $paginate->getPages(TBL_ADMIN_PANEL,$postsPerPage);

        if(array_key_exists('page',$args)){
            $currentpage = $args['page'];
            if($currentpage < 1 || $currentpage > $pages) $currentpage = 1;
        }
        else {
            $currentpage = 1;
        }


        $raw = $paginate->getPaginatedPosts(TBL_ADMIN_PANEL,5,$currentpage);
        $pages = $raw['totalpages'];
        $data = $raw['data'];
        $categories = $this->m->getCategories();
        $last10 = $this->m->getLastnPosts();
        return $this->view->render($res, 'blog/index.twig', compact('data','currentpage','pages', 'categories','last10'));
    }
    public function fullpostAction($req, $res, $args)
    {
        $data = $this->m->getPost($args['id'])[0];
        $categories = $this->m->getCategories();
        $comments = $this->m->getComments($args['id']);
        $last10 = $this->m->getLastnPosts();
        return $this->view->render($res, 'blog/fullpost.twig', compact('comments','data', 'categories','last10'));
    }
    public function searchpostAction($req, $res, $args)
    {
        $data = $this->m->getPostbyName($req->getParams());
        return $this->view->render($res, 'blog/index.twig', compact('data'));
    }

    public function searchpostCatAction($req, $res, $args)
    {
        $data = $this->m->getPostbyCategory($args['category']);
        $categories = $this->m->getCategories();
        $last10 = $this->m->getLastnPosts();
        return $this->view->render($res, 'blog/index.twig', compact('data', 'categories','last10'));
    }

    public function addcommentAction($req, $res, $args)
    {
        $comment = $req->getParams();
        $id = ($args['id']);
        $this->m->addComment($id,$comment);
        //return $this->view->render($res, 'blog/fullpost.twig', compact('id'));
        //$this->fullpostAction($req,$res,$args);
        return $res->withRedirect($this->container->router->pathFor('blogid.get',['id' => $id]));
    }
}