<?php


namespace Workspace\Models;


use Workspace\Library\Helpers;
use Workspace\Library\Model;

class CategoriesModel extends Model
{
    public function getCategories():array{
        $result = $this->db->select(
            TBL_CATEGORY,
            '*'
        );
        $errorinfo = $this->checkExecution();
        return ($errorinfo) ? $result : false;
    }

    public function deleteCatergory($id){
        $this->db->delete(
            TBL_CATEGORY,
            [
                "id"=> $id
            ]
        );
        $errorinfo = $this->checkExecution();
        return ($errorinfo) ? true : false;

    }

    public function addCategory($name){
        $this->db->insert(
            TBL_CATEGORY,
            [
                "datetime" => Helpers::DateTime(),
                "name" => $name,
                "creatorname" => DEF_AUTHOR
            ]
        );
        $errorinfo = $this->checkExecution();
        return ($errorinfo) ? true : false;
    }
}