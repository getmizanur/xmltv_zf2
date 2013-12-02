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

class Epg implements Entity
{   
    protected $filter;         

    public $epgKeyId;   
    public $epgId;      
    public $epgCrid;    
    public $epgStreamUri;    
    public $epgTitle;    
    public $epgSubTitle;    
    public $epgEpisode;    
    public $epgCategory;    
    public $epgIcon;    
    public $epgText;    
    public $epgStart;    
    public $epgDuration;    
    public $epgService;    
    public $epgServiceId;    
    public $epgChannel;    
    public $epgChannelId;    
    public $epgFile;    
    public $epgCreated;    
    public $epgCustomGeneratedVodCount;    

    public function exchangeArray($data)
    {   
        $this->epgKeyId = (      
            isset($data['epg_key_id']) ?
                $data['epg_key_id'] : "" 
        );
        $this->epgId = (
            isset($data['epg_id']) ? 
                $data['epg_id'] : ""     
        );
        $this->epgCrid = (       
            isset($data['epg_crid']) ?
                $data['epg_crid'] : ""   
        );                     
        $this->epgStreamUri = (       
            isset($data['epg_stream_uri']) ?
                $data['epg_stream_uri'] : ""   
        );  
        $this->epgTitle = (       
            isset($data['epg_title']) ?
                $data['epg_title'] : ""   
        ); 
        $this->epgSubTitle = (       
            isset($data['epg_sub_title']) ?
                $data['epg_sub_title'] : ""   
        ); 
        $this->epgEpisode = (       
            isset($data['epg_episode']) ?
                $data['epg_episode'] : ""   
        ); 
        $this->epgCategory = (       
            isset($data['epg_category']) ?
                $data['epg_category'] : ""   
        ); 
        $this->epgIcon = (       
            isset($data['epg_icon']) ?
                $data['epg_icon'] : ""   
        ); 
        $this->epgText = (       
            isset($data['epg_text']) ?
                $data['epg_text'] : ""   
        ); 
        $this->epgStart = (       
            isset($data['epg_start']) ?
                $data['epg_start'] : ""   
        ); 
        $this->epgDuration = (       
            isset($data['epg_duration']) ?
                $data['epg_duration'] : ""   
        ); 
        $this->epgService = (       
            isset($data['epg_service']) ?
                $data['epg_service'] : ""   
        ); 
        $this->epgServiceId = (       
            isset($data['epg_service_id']) ?
                $data['epg_service_id'] : ""   
        ); 
        $this->epgChannel = (       
            isset($data['epg_channel']) ?
                $data['epg_channel'] : ""   
        ); 
        $this->epgChannelId = (       
            isset($data['epg_channel_id']) ?
                $data['epg_channel_id'] : ""   
        ); 
        $this->epgFile = (       
            isset($data['epg_file']) ?
                $data['epg_file'] : ""   
        ); 
        $this->epgCreated = (       
            isset($data['epg_created']) ?
                $data['epg_created'] : ""   
        ); 
        $this->epgCustomGeneratedVodCount = (       
            isset($data['epg_custom_generated_vod_count']) ?
                $data['epg_custom_generated_vod_count'] : ""   
        ); 
    }

    public function getArrayCopy()
    {
        return array(
            "epgKeyId" => $this->epgKeyId,
            "epgId" => $this->epgId,
            "epgCrid" => $this->epgCrid,
            "epgStreamUri" => $this->epgStreamUri,
            "epgTitle" => $this->epgTitle,
            "epgSubTitle" => $this->epgSubTitle,
            "epgEpisode" => $this->epgEpisode,
            "epgCategory" => $this->epgCategory,
            "epgIcon" => $this->epgIcon,
            "epgText" => $this->epgText,
            "epgStart" => $this->epgStart,
            "epgDuration" => $this->epgDuration,
            "epgService" => $this->epgService,
            "epgServiceId" => $this->epgServiceId,
            "epgChannel" => $this->epgChannel,
            "epgChannelId" => $this->epgChannelId,
            "epgFile" => $this->epgFile,
            "epgCreated" => $this->epgCreated,
            "epgCustomGeneratedVodCount" => $this->epgCustomGeneratedVodCount,
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
                case "epgKeyId":
                case "epgId":
                case "epgCrid":
                case "epgStreamUri":
                case "epgTitle":
                case "epgSubTitle":
                case "epgEpisode":
                case "epgCategory":
                case "epgIcon":
                case "epgText":
                case "epgStart":
                case "epgDuration":
                case "epgService":
                case "epgServiceId":
                case "epgChannel":
                case "epgChannelId":
                case "epgFile":
                case "epgCreated":
                case "epgCustomGeneratedVodCount":
                    $this->$key = $value;
                    break;
                default:
                    break;
            }
        }
    }
} 
