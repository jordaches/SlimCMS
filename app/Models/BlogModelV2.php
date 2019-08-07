<?php


namespace Workspace\Models;


use Workspace\Library\Helpers;
use Workspace\Library\Model;

class BlogModelV2 extends Model
{
    public function index(int $page):array
    {
        $postCount = $this->db->count(TBL_ADMIN_PANEL);
        $pages = ceil($postCount/5);
        $limit = ($page * 5) - 5;
        $result = $this->db->select(TBL_ADMIN_PANEL,'*',[
            "ORDER" => [
                'datetime' => 'DESC'
            ],
            "LIMIT" => [$limit,5]
        ]);
        return ($this->checkExecution()) ?
            ["totalPages" => $pages, "currentPage" => $page, "data" => $result] :
            [];
    }

    public function postRetrieve(int $id){
        return $this->db->select(TBL_ADMIN_PANEL, '*', [
            "id" => $id
        ]);
    }

    public function search(string $term, int $page) : array
    {
        $postCount = $this->db->count(TBL_ADMIN_PANEL,'*',
            [
                "OR" =>
                    [
                        "datetime[~]" => $term,
                        "title[~]" => $term,
                        "category[~]" => $term,
                        "author[~]" => $term,
                        "post[~]" => $term
                    ]
            ]
        );
        $pages = ceil($postCount/5);
        $limit = ($page * 5) - 5;
        $result = $this->db->select(TBL_ADMIN_PANEL,'*',[
            "OR" =>
                [
                    "datetime[~]" => $term,
                    "title[~]" => $term,
                    "category[~]" => $term,
                    "author[~]" => $term,
                    "post[~]" => $term
                ],
            "ORDER" => [
                'datetime' => 'DESC'
            ],
            "LIMIT" => [$limit,5]
        ]);
        return ($this->checkExecution()) ?
            [
                "totalPages" => $pages, "currentPage" => $page,
                "data" => $result
            ] :
            [];
    }

    public function searchCategories(string $category, int $page) : array
    {
        $postCount = $this->db->count(TBL_ADMIN_PANEL,'*',
            [
                "category[~]" => $category,
            ]
        );
        $pages = ceil($postCount/5);
        $limit = ($page * 5) - 5;
        $result = $this->db->select(TBL_ADMIN_PANEL,'*',
        [
            "category[~]" => $category,
            "ORDER" => ['datetime' => 'DESC'],
            "LIMIT" => [$limit,5]
        ]);
        return ($this->checkExecution()) ?
            [
                "totalPages" => $pages, "currentPage" => $page,
                "data" => $result
            ] :
            [];
    }

    public function addComment(int $postid,array $commentdata){
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
}