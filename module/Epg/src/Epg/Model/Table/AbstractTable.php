<?php

namespace Epg\Model\Table;

use Zend\Db\TableGateway\TableGateway;

class AbstractTable extends TableGateway
{    
    public function fetchAll() 
    {
        return $this->select();
    }
    
    public function fetchRow($where)
    {   
        $rowset = $this->select($where);
        return $rowset->current();      
    } 
} 

