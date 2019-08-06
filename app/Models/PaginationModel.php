<?php


namespace Workspace\Models;


use Interop\Container\ContainerInterface;
use Workspace\Library\Model;

class PaginationModel extends Model
{
    /*private static $SDB,$Check;
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        self::$SDB = $this->db;
        self::$Check = $this->checkExecution();
    }*/

    public function getPaginatedPosts(string $table, int $postsPerPage ,int $page=1){
        $pages = $this->getPages($table,$postsPerPage);
        //$pages =
        $limit = ($page * $postsPerPage) - $postsPerPage;
        $result =  $this->db->select($table,'*',[
            "ORDER" => [
                'datetime' => 'DESC'
            ],
            "LIMIT" => [$limit,5]
        ]);
        return ($this->checkExecution()) ? [
            "data" => $result,
            "totalpages" => $pages,
            "currentpage" => $page
        ] : false;
    }

    public function getPages(string $table, int $postsPerPage): int
    {
        $postCount = $this->db->count($table);
        return ceil($postCount/$postsPerPage);
    }
}