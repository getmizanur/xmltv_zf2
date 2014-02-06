<?php

namespace Epg\Controller;

use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{    
    public function indexAction()
    {	        
        $config = $this->getServiceLocator()->get('Config');                     
        $this->_model->setVariables($config['iodocs']);                                                                
        return $this->_model;        
    }
}
