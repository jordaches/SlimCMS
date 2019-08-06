<?php


namespace Workspace\Models;


use Workspace\Library\Helpers;
use Workspace\Library\Model;

class AdminUsersModel extends Model
{
    public function getUsers(){
        $result = $this->db->select(TBL_ADMINS, '*');
        $errorinfo = $this->checkExecution();
        return ($errorinfo) ? $result : false;
    }

    public function addUser($params){
        $password = password_hash($params['password'], PASSWORD_DEFAULT);
        $this->db->insert(TBL_ADMINS,[
            "datetime"=> Helpers::DateTime(),
            "addedby" => DEF_AUTHOR,
            "name" => $params['fname'],
            "surname" => $params['sname'],
            "displayname" => $params['fname'] . ' '. $params['sname'],
            "username" => $params['email'],
            "password" => $password,
            "email" => $params['email']
        ]);
        return $this->checkExecution();
    }

    public function deleteUser($id){
        $this->db->delete(TBL_ADMINS, [
            "id" => $id
        ]);
        return $this->checkExecution();
    }

    public function setPassword($id,$pass){
        $password = password_hash($pass,PASSWORD_DEFAULT);
        $this->db->update(TBL_ADMINS,[
            "password" => $password
        ],[
            "id" => $id
        ]);
        return $this->checkExecution();
    }
}