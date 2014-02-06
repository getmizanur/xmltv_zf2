<?php
/**
 * Mizanur Rahman
 *
 * @link      https://www.linkedin.com/pub/mizanur-rahman/32/b15/248
 * @copyright N/A
 */

namespace TvGrabber\Model\Service;

use TvGrabber\Model\Entity\Epg as EpgEntity;
use TvGrabber\Model\Entity\File as FileEntity;
use TvGrabber\Model\Table\EpgTable;

class DvbSIService extends AbstractService
{
    protected $events;

    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers(array(
            __CLASS__,
            get_called_class(),
        ));
        $this->events = $events;
        return $this;
    }

    public function getEventManager() 
    {
        if(null === $this->events) {
            $this->setEventManager(new EventManager());
        }

        return $this->events;
    }

    public function processXml($serviceCode, $serviceNamespace, 
        $channelCode, $channelNamespace, $filePath) 
    {  
        if(!file_exists($filePath)) {
            return false;
        }

        $sm = $this->getServiceLocator(); 

        $xml = simplexml_load_file($filePath);

        $obj = $xml->xpath('/DVBSchedule/Schedule/TimePeriod/@startTime');
        $startTime = $obj[0]->startTime;

        $obj = $xml->xpath('/DVBSchedule/Schedule/TimePeriod/@endTime');
        $endTime = $obj[0]->endTime;

        $sm->get('TvGrabber\Model\Table\EpgModel')->deleteRecords(
            $startTime, $endTime, $channelNamespace, $serviceNamespace
        );

        $counter = 1;
        $result = $xml->xpath('/DVBSchedule/Schedule/ScheduleEvent');
        while(list(,$node) = each($result)) {
            $obj = $node->xpath('@schedStartTime');
            $schedStartTime = (string)$obj[0]->schedStartTime;

            $obj = $node->xpath('@schedDuration');
            $schedDuration = (string)$obj[0]->schedDuration;

            $obj = $node->xpath('DVBEITDescriptors/ShortEventDescriptor/@eventName');
            $eventName = (string)$obj[0]->eventName;

            $obj = $node->xpath('DVBEITDescriptors/ShortEventDescriptor/@text');
            $text = (string)$obj[0]->text;

            $obj = $node->xpath('DVBEITDescriptors/ContentIdentifierDescriptor/@crid');
            $crid = array();
            if(isset($obj[0]) && (string)$obj[0]->crid) {
                $crid = explode('/', (string)$obj[0]->crid);
            }

            $interval = new \DateInterval($schedDuration);
            $duration = $this->convIso8601DurationToTime($interval);

            $epg = new EpgEntity();
            $epg->epgId = new \Zend\Db\Sql\Predicate\Expression('UUID()');
            $epg->epgCrid =  (isset($crid[1]) ? $crid[1] : "");
            $epg->epgStreamUri = "";
            $epg->epgTitle = $eventName;
            $epg->epgSubTitle = ""; 
            $epg->epgEpisode = "";
            $epg->epgCategory = "";
            $epg->epgIcon = "";
            $epg->epgText = $text;
            $epg->epgStart = $schedStartTime;
            $epg->epgDuration = $duration;
            $epg->epgService = $serviceNamespace;
            $epg->epgServiceId = $serviceCode;
            $epg->epgChannel = $channelNamespace;
            $epg->epgChannelId = $channelCode;
            $epg->epgFile = $filePath;
            $epg->epgCreated = date('Y-m-d H:i:s');

            $params = array(
                'companyCode' => $serviceCode, 
                'companyNamespace' => $serviceNamespace,
            );
            $params = array_merge($params, $epg->getArrayCopy()); 
            if($sm->get('TvGrabber\Model\Table\EpgModel')->saveRow($epg)) {
                $params['logger_const'] = 'INFO';
                $this->getEventManager()->trigger('postInsert', __CLASS__, $params);
                $this->showStatus($counter, count((array)$events), " " . 
                    $channelNamespace . " - " . $channelCode);

                $sm->get('TvGrabber\Model\Table\EpgModel')
                   ->deleteOldRecords($channelNamespace, $serviceNamespace);

                $counter++;
            }else{
                $params['logger_const'] = 'ERR';
                $this->getEventManager()->trigger('postInsert', __CLASS__, $params);
            }
        }

        if($counter >= count((array)$result) + 1) {
            $file = $sm->get('TvGrabber\Model\Table\FileModel')->getFileByHash(
                sha1_file($filePath)
            );

            $file->fileProcessed = true;
            $sm->get('TvGrabber\Model\Table\FileModel')->saveRow($file);
        }

    }

    public function showStatus($done, $total, $message, $size = 30) 
    {
        static $start_time;

        if($done > $total) return;

        if(empty($start_time)) $start_time=time();
        $now = time();

        $perc=(double)($done/$total);

        $bar=floor($perc*$size);

        $status_bar="\r[";
        $status_bar.=str_repeat("=", $bar);
        if($bar<$size){
            $status_bar.=">";
            $status_bar.=str_repeat(" ", $size-$bar);
        } else {
            $status_bar.="=";
        }

        $disp=number_format($perc*100, 0);

        $status_bar.="] $disp%  $done/$total";

        //$rate = ($now-$start_time)/$done;
        //$left = $total - $done;
        //$eta = round($rate * $left, 2);

        $elapsed = $now - $start_time;

        $status_bar.= $message;

        echo "$status_bar  ";

        flush();

        if($done == $total) {
            echo "\n";
        }
    }

    private function ConvIso8601DurationToTime($interval)
    {
        $h = $interval->h * 60 * 60;
        $m = $interval->i * 60;
        $s = $interval->s;

        return ($h + $m + $s);
    }

    public function registerFile($service, $channel, $type, $filePath)
    {
        $sm = $this->getServiceLocator(); 

        if(preg_match('/XML$/', $filePath) && file_exists($filePath)) {
            $row = $sm->get('TvGrabber\Model\Table\FileModel')->getFileByHash(
                sha1_file($filePath)
            );
            if(!$row->getArrayCopy()) {
                $file = new FileEntity(); 
                $file->fileService = $service;
                $file->fileChannel = $channel;
                $file->filePath = $filePath;
                $file->fileHash  = sha1_file($filePath);
                $file->fileType = $type;
                $file->fileProcessed = false;
                $file->fileCreated = date('Y-m-d H:i:s');

                $sm->get('TvGrabber\Model\Table\FileModel')->saveRow($file);
            }
        }
    }

    public function timeToSeconds($string)
    {
        $t = explode(':', $string);
        return $t[0] * 60  + $t[1];
    }
}
