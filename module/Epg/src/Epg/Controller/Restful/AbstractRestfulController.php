<?php

namespace Epg\Controller\Restful;

use Zend\Mvc\Controller\AbstractRestfulController 
    as BaseAbstractRestfulController;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Navigation\Page\Mvc as PageMvc;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Registry;

abstract class AbstractRestfulController 
    extends BaseAbstractRestfulController
{
    const ERROR_FLASH_MESSENGER = 'error_flash_messages';
    const VALID_FLASH_MESSENGER= 'valid_flash_messages';
    const DEFAULT_FLASH_MESSENGER = 'default_flash_messages';

    protected $_model = null;

    protected $_allowedMethods = array (
        'GET', 
        'POST', 
        'PUT', 
        'DELETE',
    );

    protected $_acceptCriteria = array( 
        'Zend\View\Model\JsonModel' => array(
            'application/json', 
            'application/jsonp', 
            'application/javascript'
        ),            
        'Zend\View\Model\ViewModel' => array('*/*'),
    );
  
    public function dispatch(Request $request, Response $response = null)
    {   
        $this->getEventManager()->attach(
            MvcEvent::EVENT_DISPATCH, 
            array(
                $this, 
                'postDispatch'
            ), 
            -100
        );
        $this->getEventManager()->attach(
            MvcEvent::EVENT_DISPATCH, 
            array(
                $this, 
                'preDispatch'
            ), 
            100
        );

        return parent::dispatch($request, $response);
    }
    
    public function postDispatch(MvcEvent $e)
    {   
        $result = $e->getResult();
        if($result instanceof ViewModel) {
            $vars = is_array($result->getVariables()) ? 
                        $result->getVariables() : array();

            if(0 == strcmp($e->getRequest()->getQuery()->get('verbos'), 'y')) {
                $result->setVariables(array_merge(
                    array(
                        self::ERROR_FLASH_MESSENGER => 
                            $this->plugin('flashmessenger')
                                 ->getErrorMessages(),
                        self::VALID_FLASH_MESSENGER => 
                            $this->plugin('flashmessenger')
                                 ->getValidMessages(),
                        self::DEFAULT_FLASH_MESSENGER => 
                            $this->plugin('flashmessenger')
                                 ->getDefaultMessages(),
                    ),
                    $vars
                ));
            }
        }
    }

    public function preDispatch(MvcEvent $e) 
    {
        $request = $e->getRequest();
        $query = $request->getQuery();
        $match = $e->getRouteMatch();

        if($query->get('format') == 'json'
            || $match->getParam('format') == '.json') {
            $this->_model = new JsonModel();
        }else{
            $this->_model = new JsonModel();
        }
    }

    public function getResponseWithHeader()
    {
        $response = $this->getResponse();
        $response->getHeaders()
                 ->addHeaderLine('Access-Control-Allow-Origin','*')
                 ->addHeaderLine(
                    'Access-Control-Allow-Methods',
                    implode(' ', $this->allowedMethods)
                 );

        return $response;
    }
}
