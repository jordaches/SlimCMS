<?php


namespace Workspace\Controllers;


use Workspace\Library\Controller;
use Workspace\Library\Helpers;
use Workspace\Models\BlogModelV2;

class BlogV2 extends Controller
{
    public function indexAction($req,$res,$args){
        $params = array_key_exists('page', $args) ? $args['page'] : 1;
        $db = new BlogModelV2($this->container);
        $data = $db->index($params);
        Helpers::DebugDie($data);
        //TODO: Implement VIEW
    }

    public function postAction($req,$res,$args){
        $db = new BlogModelV2($this->container);
        $data = $db->postRetrieve($args['id']);
        Helpers::DebugDie($data);
        //TODO: Implement VIEW
    }

    public function searchAction($req,$res,$args){
        $db = new BlogModelV2($this->container);
        $page = array_key_exists('page', $args) ? $args['page'] : 1;
        $data = $db->search($args['term'],$page);
        Helpers::DebugDie($data);
        //TODO: Implement VIEW
    }

    public function categoriesAction($req,$res,$args){
        $db = new BlogModelV2($this->container);
        $page = array_key_exists('page', $args) ? $args['page'] : 1;
        $data = $db->searchCategories($args['category'], $page);
        Helpers::DebugDie($data);
        //TODO: Implement VIEW
    }

    public function addCommentAction($req,$res,$args){
        $db = new BlogModelV2($this->container);
        $comment = $req->getParams();
        $id = ($args['id']);
        $db->addComment($id,$comment);
        Helpers::DebugDie($db->addComment($id,$comment));
        //TODO: Implement VIEW
    }
}