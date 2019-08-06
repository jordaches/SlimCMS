<?php


namespace Workspace\Models;


use Workspace\Library\Model;
use Workspace\Library\Helpers;

class DashboardModel extends Model
{
    public function getPosts():array //Maps to index
    {
        $result = $this->db->select(TBL_ADMIN_PANEL,'*');
        $errorinfo = $this->checkExecution();
        return ($errorinfo) ? $result : false;
    }

    public function deletePost($id)
    {
        $postData = $this->getPost($id);
        $imageToRemove = $postData[0]['image'];
        rename(UPLOADS_WIN_CURRENT.'\\'.$imageToRemove,UPLOADS_WIN_DELETE.'\\'.$imageToRemove);
        $this->db->delete(TBL_ADMIN_PANEL, ['id' => $id]);
        $errorinfo = $this->checkExecution();
        return ($errorinfo) ? true : false;
    }

    public function addPost($data)
    {
        $this->db->insert(TBL_ADMIN_PANEL, [
            "title" => $data['Title'],
            "datetime" => Helpers::DateTime(),
            "category" => $data['Category'],
            "author" => DEF_AUTHOR,
            "image" => $data['imageName'],
            "post" => $data['Post']
        ]);
        $errorinfo = $this->checkExecution();
        return ($errorinfo) ? true : false;
    }

    public function getPost($id){
        $result = $this->db->select(TBL_ADMIN_PANEL,'*',["id" => $id]);
        $errorinfo = $this->checkExecution();
        return ($errorinfo) ? $result : false;
    }

    public function updatePost($id,$params){
        if($params['image'] == null){
            $this->db->update(TBL_ADMIN_PANEL,[
                "title" => $params['Title'],
                "datetime" => Helpers::DateTime(),
                "category" => $params['Category'],
                "post" => $params['Post'],
            ],
                [
                    "id" => $id
                ]);
        }
        else{
            $postData = $this->getPost($id);
            $imageToRemove = $postData[0]['image'];
            rename(UPLOADS_WIN_CURRENT.'\\'.$imageToRemove,UPLOADS_WIN_DELETE.'\\'.$imageToRemove);
            $this->db->update(TBL_ADMIN_PANEL,[
                "title" => $params['Title'],
                "datetime" => Helpers::DateTime(),
                "category" => $params['Category'],
                "post" => $params['Post'],
                "image" => $params['image'],
            ],
                [
                    "id" => $id
                ]);
        }
        return $this->checkExecution();
    }
}