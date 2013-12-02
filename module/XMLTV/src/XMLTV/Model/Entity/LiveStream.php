<?php
/**
 * Mizanur Rahman
 *
 * @link      https://www.linkedin.com/pub/mizanur-rahman/32/b15/248
 * @copyright N/A
 */

namespace XMLTV\Model\Entity;

use Zend\InputFilter\Factory;  
use Zend\InputFilter\InputFilterInterface;

use Mm\Common\Entity;          

class LiveStream implements Entity
{   
    protected $filter;         

    public $id;
    public $name;
    public $epgName;
    public $type;
    public $liveAppName;
    public $moddate;
    public $modifiedBy;
    public $companyId;
    public $epgUrl;
    public $status;
    public $retention;
    public $epgSource;
    public $formatId;
    public $epgLogo;
    public $logo;
    public $primaryCaptureSiteId;
    public $secondaryCaptureSiteId;
    public $order;
    public $onlineStatus;
    public $key;
    public $genre;
    public $newUntil;
    public $broadcastFrom;
    public $broadcastTo;
    public $version;

    public function exchangeArray($data)
    {
        $this->id = (      
            isset($data['id']) ?
                $data['id'] : "" 
        );
        $this->name = (      
            isset($data['name']) ?
                $data['name'] : "" 
        );
        $this->epgName = (      
            isset($data['epg_name']) ?
                $data['epg_name'] : "" 
        );
        $this->type = (      
            isset($data['type']) ?
                $data['type'] : "" 
        );
        $this->liveAppName = (      
            isset($data['live_app_name']) ?
                $data['live_app_name'] : "" 
        );
        $this->moddate = (      
            isset($data['moddate']) ?
                $data['moddate'] : "" 
        );
        $this->modifiedBy = (      
            isset($data['modified_by']) ?
                $data['modified_by'] : "" 
        );
        $this->companyId = (      
            isset($data['company_id']) ?
                $data['company_id'] : "" 
        );
        $this->epgUrl = (      
            isset($data['epg_url']) ?
                $data['epg_url'] : "" 
        );
        $this->status = (      
            isset($data['status']) ?
                $data['status'] : "" 
        );
        $this->retention = (      
            isset($data['retention']) ?
                $data['retention'] : "" 
        );
        $this->epgSource = (      
            isset($data['epg_source']) ?
                $data['epg_source'] : "" 
        );
        $this->formatId = (
            isset($data['format_id']) ?
                $data['format_id'] : "" 
        );
        $this->epgLogo = (
            isset($data['epg_logo']) ?
                $data['epg_logo'] : "" 
        );
        $this->logo = (
            isset($data['logo']) ?
                $data['logo'] : "" 
        );
        $this->primaryCaptureSiteId = (
            isset($data['primary_capture_site_id']) ?
                $data['primary_capture_site_id'] : "" 
        );
        $this->secondaryCaptureSiteId = (
            isset($data['secondary_capture_site_id']) ?
                $data['secondary_capture_site_id'] : "" 
        ); 
        $this->order = (
            isset($data['order']) ?
                $data['order'] : "" 
        ); 
        $this->onlineStatus = (
            isset($data['online_status']) ?
                $data['online_status'] : "" 
        );
        $this->key = (
            isset($data['key']) ?
                $data['key'] : "" 
        );
        $this->genre = (
            isset($data['genre']) ?
                $data['genre'] : "" 
        );
        $this->newUntil = (
            isset($data['new_until']) ?
                $data['new_until'] : "" 
        );
        $this->broadcastFrom = (
            isset($data['broadcast_from']) ?
                $data['broadcast_from'] : "" 
        );
        $this->broadcastTo = (
            isset($data['broadcast_to']) ?
                $data['broadcast_to'] : "" 
        );
        $this->version = (
            isset($data['version']) ?
                $data['version'] : "" 
        );
    } 

    public function getArrayCopy()
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'epgName' => $this->epgName,
            'type' => $this->type,
            'liveAppName' => $this->liveAppName,
            'moddate' => $this->moddate,
            'modifiedBy' => $this->modifiedBy,
            'companyId' => $this->companyId,
            'epgUrl' => $this->epgUrl,
            'status' => $this->status,
            'retention' => $this->retention,
            'epgSource' => $this->epgSource,
            'formatId' => $this->formatId,
            'epgLogo' => $this->epgLogo,
            'logo' => $this->logo,
            'primaryCaptureSiteId' => $this->primaryCaptureSiteId,
            'secondaryCaptureSiteId' => $this->secondaryCaptureSiteId,
            'order' => $this->order,
            'onlineStatus' => $this->onlineStatus,
            'key' => $this->key,
            'genre' => $this->genre,
            'newUntil' => $this->newUntil,
            'broadcastFrom' => $this->broadcastFrom,
            'broadcastTo' => $this->broadcastTo,
            'version' => $this->version
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
                case 'id':
                case 'name':
                case 'epgName': 
                case 'type':
                case 'liveAppName': 
                case 'moddate':
                case 'modifiedBy': 
                case 'companyId': 
                case 'epgUrl':
                case 'status':
                case 'retention':
                case 'epgSource':
                case 'formatId':
                case 'epgLogo':
                case 'logo':
                case 'primaryCaptureSiteId':
                case 'secondaryCaptureSiteId':
                case 'order':
                case 'onlineStatus':
                case 'key':
                case 'genre':
                case 'newUntil':
                case 'broadcastFrom':
                case 'broadcastTo':
                case 'version':
                    $this->$key = $value;
                    break;
                default:
                    break;
            }
        }
    }
}
