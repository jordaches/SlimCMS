<?php


namespace Workspace\Library;


use Interop\Container\ContainerInterface;

abstract class Model
{
    protected $db;
    public function __construct(ContainerInterface $container)
    {
        $this->db = $container['db'];
    }

    public function checkExecution(){
        $errorInfo = $this->db->error();
        return ($errorInfo[0] == '00000') ? true : false;
    }

    public function getError(){
        return $this->db->error();
    }
}