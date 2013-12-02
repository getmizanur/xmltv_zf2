<?php
/**
 * Mizanur Rahman
 *
 * @link      https://www.linkedin.com/pub/mizanur-rahman/32/b15/248
 * @copyright N/A
 */

namespace XMLTV\Model\Table;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Basic table gateway
 */
class AbstractTable extends AbstractTableGateway implements 
    ServiceLocatorAwareInterface
{    
    protected $serviceLocator;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
    /**
     * Fetch all records 
     *
     * @return array
     */
    public function fetchAll() 
    {
        return $this->select();
    }
    
    /**
     * Fetch one record
     *
     * @return object
     */
    public function fetchRow($where)
    {   
        $rowset = $this->select($where);
        return $rowset->current();      
    } 
} 
