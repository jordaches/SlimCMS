<?php


namespace Workspace\Models;


use Workspace\Library\Helpers;
use Workspace\Library\Model;

class BlogModel extends Model
{
    public function getPosts(int $page = 1):array //Maps to index
    {
        $postcount =  count($this->db->select(TBL_ADMIN_PANEL,'id')); //Use native count from Medoo
        $pages = ceil($postcount/5);
        $limit = ($page * 5) - 5;
        $result =  $this->db->select(TBL_ADMIN_PANEL,'*',[
            "ORDER" => [
                'datetime' => 'DESC'
            ],
            "LIMIT" => [$limit,5]
        ]);
        $errorinfo = $this->checkExecution();
        return ($errorinfo) ? ["data" => $result, "totalpages" => $pages, "currentpage" => $page] : false;
        //return ($errorinfo) ? $result : false;
        //$pagination = new PaginationModel($this->container);

       // return $pagination->getPaginatedPosts(TBL_ADMIN_PANEL,5, $page);
    }


    public function getComments($id):array //Maps to index
    {
        $result = $this->db->select(TBL_COMMENTS,'*',[
            "AND" => [
                'admin_panel_id' => $id,
                'status' => 'ON'
            ],
            "ORDER" => [
                'datetime' => 'DESC'
            ]
        ]);
        $errorinfo = $this->checkExecution();
        return ($errorinfo) ? $result : false;
    }

    public function getCategories():array{
        $result =  $this->db->select(TBL_CATEGORY,'name');
        $errorinfo = $this->checkExecution();
        return ($errorinfo) ? $result : false;
    }

    public function getPost($id)
    {
        $result = $this->db->select(TBL_ADMIN_PANEL,'*', ['id' => $id]);
        $errorinfo = $this->checkExecution();
        return ($errorinfo) ? $result : false;
    }

    public function getPostbyName($searchval):array
    {
        $searchval = $searchval['search'];
        $result = $this->db->select(TBL_ADMIN_PANEL,'*', [
            "OR" => [
                "datetime[~]" => $searchval,
                "title[~]" => $searchval,
                "category[~]" => $searchval,
                "author[~]" => $searchval,
                "post[~]" => $searchval
            ]
        ]);
        $errorinfo = $this->checkExecution();
        return ($errorinfo) ? $result : false;
    }
    public function addComment($postid,$commentdata){
        $this->db->insert(TBL_COMMENTS,[
            'datetime' => Helpers::DateTime(),
            'name' => $commentdata['Name'],
            'email' => $commentdata['Email'],
            'comment' => $commentdata['Comment'],
            'status' => 'OFF',
            'admin_panel_id' => $postid
        ]);
        $errorinfo = $this->checkExecution();
        return ($errorinfo) ? true : false;
    }
    public function getPostbyCategory($category){
        $result = $this->db->select(TBL_ADMIN_PANEL,'*', ['category' => $category]);
        $errorinfo = $this->checkExecution();
        return ($errorinfo) ? $result : false;
    }
    public function getLastnPosts($val=10){
        $result = $this->db->select(TBL_ADMIN_PANEL,'*', [
            "ORDER" => [
                "datetime" =>"DESC"
            ],
            "LIMIT" => $val
        ]);
        $errorinfo = $this->checkExecution();
        return ($errorinfo) ? $result : false;
    }
}