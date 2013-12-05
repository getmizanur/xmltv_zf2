<?php
/**
 * Mizanur Rahman
 *
 * @link      https://www.linkedin.com/pub/mizanur-rahman/32/b15/248
 * @copyright N/A
 */

namespace TvGrabber\Model\Table;

use Zend\Db\Adapter\Adapter;   
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;        
use Zend\Db\Sql\Where;         
use Zend\Db\Sql\Delete;         
use Zend\Db\Sql\Expression;
use Zend\InputFilter\Factory;  

use Mm\Util\StringUtil;

use TvGrabber\Model\Entity\File as FileEntity;

class FileTable extends AbstractTable 
{   
    protected $table;          
    protected $tableName;      

    public function __construct($table, Adapter $dbAdapter) 
    {   
        $this->table = $this->tableName = $table;
        $this->adapter = $dbAdapter;    

        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new FileEntity());

        $this->initialize();   
    }

    public function getFileById($id)
    {
        $row = $this->fetchRow(array(
            'file_id' => $id
        ));

        if(!$row) {
            return new \ArrayObject();
        }

        return $row;
    }

    public function getFileByHash($hash)
    {
        $row = $this->fetchRow(array(
            'file_hash' => $hash
        ));

        if(!$row) {
            return new \ArrayObject();
        }

        return $row;
    }


    public function getFileByFilePath($path)
    {
        $row = $this->fetchRow(array(
            'file_path' => $path
        ));

        if(!$row) {
            return new \ArrayObject();
        }

        return $row;
    }

    public function getFilesByType($service, $channel, $type)
    {
        $resultSet = $this->select(array(
            'file_service' => strtolower($service),
            'file_channel' => $channel,
            'file_type' => $type,
            'file_processed' => false
        ));
        
        if(1 > $resultSet->count()) {
            return null;
        }
        
        $data = array();
        foreach($resultSet as $row){
            $data[$row->fileId] = $row; 
        }

        return $data;
    }

    public function saveRow(FileEntity $file)
    {
        $data = $file->getArrayCopy();

        $id = $file->fileId;

        $keys = array_keys($data);
        $values = array_values($data);

        array_walk($keys, function(&$item, $key) {
            $item = StringUtil::decamelize($item, '_');
        });

        $data = array_combine($keys, $values);

        $lastInsert = null;
        if ($id == 0) {
            $this->insert($data);
            $lastInsert = $this->lastInsertValue;
        } elseif ($this->getFileById($id)) {
            $this->update(
                $data,
                array(
                    'file_id' => $id,
                )   
            );  
            $lastInsert = $id;
        } else {
            throw new \Exception('Unable to insert or update record');
        }   
        
        return $lastInsert;
    } 
}
