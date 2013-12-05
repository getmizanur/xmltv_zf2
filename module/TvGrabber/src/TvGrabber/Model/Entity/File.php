<?php
/**
 * Mizanur Rahman
 *
 * @link      https://www.linkedin.com/pub/mizanur-rahman/32/b15/248
 * @copyright N/A
 */

namespace TvGrabber\Model\Entity;

use Zend\InputFilter\Factory;  
use Zend\InputFilter\InputFilterInterface;

use Mm\Common\Entity;          

class File implements Entity
{   
    protected $filter;      

    public $fileId;
    public $fileService;
    public $fileChannel;
    public $filePath;
    public $fileHash;
    public $fileType;
    public $fileProcessed;
    public $fileCreated; 

    public function exchangeArray($data)
    {
        $this->fileId = (
            isset($data['file_id']) ?
                $data['file_id'] : ""
        );
        $this->fileService = (
            isset($data['file_service']) ?
                $data['file_service'] : ""
        );
        $this->fileChannel = (
            isset($data['file_channel']) ?
                $data['file_channel'] : ""
        );
        $this->filePath = (
            isset($data['file_path']) ?
                $data['file_path'] : ""
        );
        $this->fileHash = (
            isset($data['file_hash']) ?
                $data['file_hash'] : ""
        );
        $this->fileType = (
            isset($data['file_type']) ?
                $data['file_type'] : ""
        );
        $this->fileProcessed = (
            isset($data['file_processed']) ?
                $data['file_processed'] : ""
        );
        $this->fileCreated = (
            isset($data['file_created']) ?
                $data['file_created'] : ""
        );
    }

    public function getArrayCopy()
    {
        return array(
            'fileId' => $this->fileId,
            'fileService' => $this->fileService,
            'fileChannel' => $this->fileChannel,
            'filePath' => $this->filePath,
            'fileHash' => $this->fileHash,
            'fileType' => $this->fileType,
            'fileProcessed' => $this->fileProcessed,
            'fileCreated' => $this->fileCreated    
        );
    }

    public function setInputFilter(InputFilterInterface $filter)
    {
        $this->filter = $filter;

        return $this;
    }

    public function getInputFilter()
    {
        return $this->filter;
    }

    public function isValid()
    {
        if(null === $this->filter) {
            throw \Exception("Input filter is not set");
        }

        $filter = $this->getInputFilter();
        $filter->setData($this->getArrayCopy());
        $valid = $filter->isValid();

        if($valid) {
            $this->fromArray($filter->getValues());
        }

        return $valid;
    }

    public function fromArray($data)
    {
        foreach($data as $key => $value) {
            switch($key) {
                case 'fileId':
                case 'fileService':
                case 'fileChannel':
                case 'filePath':
                case 'fileHash':
                case 'fileType':
                case 'fileProcessed':
                case 'fileCreated':
                    $this->$key = $value;
                    break;
                default:
                    break;
            }
        }
    }
}
