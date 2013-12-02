<?php
/**
 * Mizanur Rahman
 *
 * @link      https://www.linkedin.com/pub/mizanur-rahman/32/b15/248
 * @copyright N/A
 */

namespace XMLTV\Model\Table;

use Zend\Db\Adapter\Adapter;   
use Zend\Db\ResultSet\ResultSet;
use Zend\InputFilter\Factory;  
use Zend\Db\Sql\Select;        
use Zend\Db\Sql\Where;         
use Zend\Db\Sql\Delete;         
use Zend\Db\Sql\Expression;

use XMLTV\Model\Entity\LiveStream as LiveStreamEntity;
use XMLTV\Util\StringUtil;

class LiveStreamsTable extends AbstractTable 
{   
    protected $table;          
    protected $tableName;      

    public function __construct($table, Adapter $dbAdapter) 
    {   
        $this->table = $this->tableName = $table;
        $this->adapter = $dbAdapter;    

        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new LiveStreamEntity());

        $this->initialize();   
    }

    public function getLiveStreamById($id)
    {
        $row = $this->fetchRow(array(
            'id' => $id
        ));

        if(!$row) {
            return new \ArrayObject();
        }

        return $row;
    }

    
    public function getLiveStreamsByCompanyId($id)
    {
        $resultSet = $this->select(array(
            'company_id' => $id
        ));

        if(1 > $resultSet->count()) {
            throw new InvalidException("No records found");
        }

        $data = array();
        foreach($resultSet as $row){
            $data[$row->id] = $row; 
        }

        return $data;
    }

    public function save(LiveStream $liveStream)
    {
        $data = $liveStream->getArrayCopy();

        $id = $liveStream->id;

        $keys = array_keys($data);
        $values = array_values($data);

        array_walk($keys, function(&$items, $key) {
            $item = StringUtil::decamelize($item, '_');
        });

        $data = array_combine($keys, $values);
        
        $lastInsert = null;
        if ($id == 0) {
            $this->insert($data);
            $lastInsert = $this->getLastInsertValue();
        } elseif ($this->getLiveStreamById($id)) {
            $this->update(
                $data,
                array(
                    'id' => $id,
                )   
            );  
            $lastInsert = $id;
        } else {
            throw new \Exception('Unable to insert or update record');
        }   
        
        return $lastInsert;
    }   
}
