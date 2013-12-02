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
use Zend\Db\Sql\Predicate\Expression;         

use XMLTV\Model\Entity\Epg as EpgEntity;
use XMLTV\Util\StringUtil;
use XMLTV\Util\DbUtil;

class EpgTable extends AbstractTable 
{   
    protected $table;          
    protected $tableName;      

    public function __construct($table, Adapter $dbAdapter) 
    {   
        $this->table = $this->tableName = $table;
        $this->adapter = $dbAdapter;    

        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new EpgEntity());

        $this->initialize();   
    }

    public function getEpgByKeyId($id)
    {
        $row = $this->fetchRow(array(
            'epg_key_id' => $id
        ));

        if(!$row) {
            return new \ArrayObject();
        }

        return $row;
    }

    public function deleteOldRecords($channel, $service)
    {
        $delete = $this->delete(function(Delete $delete) 
            use ($channel, $service) {
            $predicate = new Where();
            $delete->where(
                array(
                    $predicate->addPredicate(
                        new Expression("DATE(epg_start) < (NOW() - INTERVAL 9 DAY)")
                    ),
                    $predicate->equalTo('epg_channel', $channel),
                    $predicate->equalTo('epg_service', $service)
                ),
                \Zend\Db\Sql\Predicate\PredicateSet::OP_AND
            );
        });

        return $delete;
    }

    public function deleteRecords($start, $stop, $channel, $service)
    {
        $delete = $this->delete(function(Delete $delete) 
            use ($start, $stop, $channel, $service) {
            $predicate = new Where();
            $delete->where(
                array(
                    $predicate->greaterThanOrEqualTo('epg_start', $start), 
                    $predicate->addPredicate(
                        new Expression("FROM_UNIXTIME(UNIX_TIMESTAMP(epg_start) + epg_duration) <= '$stop'")
                    ),
                    $predicate->equalTo('epg_channel', $channel),
                    $predicate->equalTo('epg_service', $service)
                ),
                \Zend\Db\Sql\Predicate\PredicateSet::OP_AND
            );
        });

        return $delete;
    }

    public function saveRow(EpgEntity $epg)
    {
        $data = $epg->getArrayCopy();

        $id = $epg->epgKeyId;

        $keys = array_keys($data);
        $values = array_values($data);

        array_walk($keys, function(&$item, $key) {
            $item = StringUtil::decamelize($item, '_');
        });

        $data = array_combine($keys, $values);
        
        $lastInsert = null;
        if ($id == 0) {
            $this->insert($data);
            return "test";
            $lastInsert = $this->getLastGeneratedValue();
        } elseif ($this->getEpgByKeyId($id)) {
            $this->update(
                $data,
                array(
                    'epg_key_id' => $id,
                )   
            );  
            $lastInsert = $id;
        } else {
            throw new \Exception('Unable to insert or update record');
        }   
        
        return $lastInsert;
    }   
}
