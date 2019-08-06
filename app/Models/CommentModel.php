<?php


namespace Workspace\Models;


use Interop\Container\ContainerInterface;
use Workspace\Library\Helpers;
use Workspace\Library\Model;

class CommentModel extends Model
{
    public function getComments(){
        $result =  $this->db->select(TBL_COMMENTS, '*');
        $errorinfo = $this->checkExecution();
        return ($errorinfo) ? $result : false;

    }

    public function approveComment($id){
        $this->db->update(TBL_COMMENTS,
            [
               "status" =>'ON',
               "approvedby" => DEF_AUTHOR
            ],
            [
                "id" => $id
            ]);
        $errorinfo = $this->checkExecution();
        return ($errorinfo) ? true : false;
    }

    public function disapproveComment($id)
    {
        $this->db->update(TBL_COMMENTS,
            [
                "status" => 'OFF',
                "approvedby" => DEF_AUTHOR
            ],
            [
                "id" => $id
            ]);
        $errorinfo = $this->checkExecution();
        return ($errorinfo) ? true : false;
    }

    public function deleteComment($id){
        $this->db->delete(
            TBL_COMMENTS,
            [
                "id"=> $id
            ]
        );
        $errorinfo = $this->checkExecution();
        return ($errorinfo) ? true : false;
    }
}