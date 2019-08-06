<?php


namespace Workspace\Library;


use Interop\Container\ContainerInterface;

abstract class Controller
{
    protected $container, $view, $messages;
    public function __construct(ContainerInterface $container){
        $this->view = $container['view'];
        $this->container = $container;
        $this->messages = $container['flash'];
    }

    public function addErrorMessage($message){
        $this->messages->addMessage('error',$message);
    }
    public function addSuccessMessage($message){
        $this->messages->addMessage('success',$message);
    }
    public function addWarningMessage($message){
        $this->messages->addMessage('warning',$message);
    }

    public function getWarningMessages(){}

    public function getErrorMessages(){}

    public function getSuccessMessages(){}

    public function getAllMessages(){
        return $this->messages->getMessages();
    }
}