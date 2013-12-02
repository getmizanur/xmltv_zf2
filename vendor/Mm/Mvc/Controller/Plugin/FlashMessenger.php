<?php
namespace Mm\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\FlashMessenger as BaseFlashMessenger;

class FlashMessenger extends BaseFlashMessenger
{
    protected $namespaceError = 'error_message';
    protected $namespaceValid = 'valid_message';
    
    public function addErrorMessage($message)
    {
        $namespace = $this->getNamespace();
        $this->namespace = $this->namespaceError;
        parent::addMessage($message);
        $this->namespace = $namespace;
        
        return $this;
    }
    
    public function addValidMessage($message)
    {
        $namespace = $this->getNamespace();
        $this->namespace = $this->namespaceValid;
        parent::addMessage($message);
        $this->namespace = $namespace;
        
        return $this;
    }
    
    public function getErrorMessages($clean=true)
    {
        $namespace = $this->getNamespace();
        $this->namespace = $this->namespaceError;
        
        $messages = $this->getCurrentMessages();
        if($clean) {
            $this->clearCurrentMessages();
        }
        $this->namespace = $namespace;
        
        return $messages;
    }
    
    public function getValidMessages($clean=true)
    {
        $namespace = $this->getNamespace();
        $this->namespace = $this->namespaceValid;
        
        $messages = $this->getCurrentMessages();
        if($clean) {
            $this->clearCurrentMessages();
        }
        $this->namespace = $namespace;
        
        return $messages;
    }
    
    public function getDefaultMessages($clean=true)
    {
        $messages = $this->getCurrentMessages();
        if($clean) {
            $this->clearCurrentMessages();
        }
        
        return $messages;
    }
}
