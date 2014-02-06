<?php

namespace Epg\Controller\Restful;

use Epg\Controller\Restful\AbstractRestfulController;
use Epg\Model\Entity\Device;
use Epg\Listener\LogListener;

use Zend\View\Model\JsonModel;
use Zend\InputFilter\Factory;
use Zend\Session\Container;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature\EventFeature;

class EpgController extends AbstractRestfulController
{    
    public function getList()
    {
        $response = array();

        $sm = $this->getServiceLocator();
        $match = $sm->get('Application')
                    ->getMvcEvent()                 
                    ->getRouteMatch();  

        $model = $sm->get('Epg\ModelTable\EpgModel');
        $row = $model->getEpgByKeyIds(16935);
        $factory = new Factory();
        $inputFilter = $factory->createInputFilter(array (
            'service' => array (
                'name' => 'service',
                'required' => true,
                'filters' => array (
                    array ('name' => 'StripTags'),
                    array ('name' => 'StringTrim'),
                    array ('name' => 'HtmlEntities')
                ),
                'validators' => array (
                    array (
                        'name' => 'not_empty',
                        'options' => array(
                            'message' => "Service cannot be empty"
                        )
                    ),
                    array (
                        'name' => 'in_array',
                        'options' => array (
                            'haystack' => array(
                                'tvplayer', 'boxtv'
                            )
                        )
                    ),                
                )            
            ),
            'outlet' => array (
                'name' => 'service',
                'required' => false,
                'filters' => array (
                    array ('name' => 'StripTags'),
                    array ('name' => 'StringTrim'),
                    array ('name' => 'HtmlEntities')
                ),
                'validators' => array (
                    array (
                        'name' => 'alnum',
                        'options' => array(
                            'message' => "Outlet is not valid alpanumeric character"
                        )
                    ),
                )            
            ),
        ));

        $inputFilter->setData($match->getParams());

        if($inputFilter->isValid()) {
            $data = $sm->get('Epg\Model\Service\EpgService')
                ->getEpgData($inputFilter->getValue('service'));
            $response = array(
                'broadcasts' => $data
            );
        }else{
            foreach($inputFilter->getInvalidInput() as $error) {
                $this->plugin('flashmessenger')
                    ->addErrorMessage($error->getMessages());
            }
        }
        
        $this->_model->setVariables($response);

        return $this->_model;
    }  

    public function get($id)
    {
        if(!$id) {
            $this->getList();
        }

        return $this->_model;
    }

    public function create($data)
    {
        return $this->_model;
    }

    public function update($id, $data)
    {
        return $this->_model;
    }

    public function delete($id)
    {
        return $this->_model;
    }
}
