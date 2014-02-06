<?php
/**
 * Mizanur Rahman
 *
 * @link      https://www.linkedin.com/pub/mizanur-rahman/32/b15/248
 * @copyright N/A
 */

namespace Epg\Model\Table;

use Zend\Db\TableGateway\Feature\EventFeature;
use Zend\Db\Adapter\Adapter;   
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;        
use Zend\Db\Sql\Where;         
use Zend\Db\Sql\Delete;         
use Zend\Db\Sql\Predicate\Expression;         
use Zend\InputFilter\Factory;  

use Mm\Util\StringUtil;

use Epg\Model\Entity\Epg as EpgEntity;

class EpgTable extends AbstractTable 
{   
    protected $table;          
    protected $tableName;      

    public function __construct($table, Adapter $dbAdapter, $feature) 
    {   
        parent::__construct($table, $dbAdapter, $feature);

        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new EpgEntity());

        $this->initialize();   
    }

    public function getEpgByKeyIds($id)
    {
        $row = $this->fetchRow(array(
            'epg_key_id' => $id
        ));

        if(!$row) {
            return new \ArrayObject();
        }

        return $row;
    }

    public function getEpgChannels($service)
    {
        $rows = $this->select(function(Select $select) use ($service){
            $select->columns(array(new Expression('DISTINCT(epg_channel) as epg_channel')));
            $predicate  = new Where();
            $select->where(
                array(
                    $predicate->equalTo('epg_service', $service)
                ),
                \Zend\Db\Sql\Predicate\PredicateSet::OP_AND
            );
        });

        if(!$rows) {
            return false;
        }

        $data = array();
        foreach($rows as $row) {
            $data[] = $row->epgChannel;
        }

        return $data;
    }

    public function getEpgByService($service)
    {
        $rows = $this->select(array(
            'epg_service' => $service
        ));

        if(!$rows) {
            return new \ArrayObject();
        }

        return $rows;
    }

    public function getProgrammesByService($service, $channel, $date = null) 
    {
        if(NULL === $date) {
            $date = date('Y-m-d 06:00');
        }
        $rows = $this->select(function(Select $select) use ($service, $channel, $date){
            $select->columns(array('epg_title', 'epg_start', 'epg_duration'));
            $predicate  = new Where();
            $select->where(
                array(
                    $predicate->equalTo('epg_service', $service),
                    $predicate->equalTo('epg_channel', $channel),
                    $predicate->expression('epg_start >= ?', $date),
                    $predicate->expression(
                        'FROM_UNIXTIME(UNIX_TIMESTAMP(epg_start) + epg_duration) <= ?', 
                        date('Y-m-d 06:00', strtotime("+1 day", strtotime($date)))),
                ),
                \Zend\Db\Sql\Predicate\PredicateSet::OP_AND
            );
        });

        if(!$rows) {
            return false;
        }

        $data = array();
        foreach($rows as $row) {
            $data[] = array(
                'epg_title' => $row->epgTitle,
                'epg_start' => date(DATE_ATOM, strtotime($row->epgStart)),
                'epg_duration' => $row->epgDuration,
            );
        }

        return $data;
    }
}
