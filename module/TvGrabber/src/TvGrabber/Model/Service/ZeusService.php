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

class ZeusService extends AbstractService
{
    public function processXml($serviceCode, $serviceNamespace, 
        $channelCode, $channelNamespace, $filePath) 
    {  
        if(!file_exists($filePath)) {
            return false;
        }

        $sm = $this->getServiceLocator(); 

        $xml = simplexml_load_file($filePath);

        $events = $xml->xpath('/phoenix7/PlanEvent');
        $counter = 1;
        
        $first = (array)$events[0];
        $planDate = $first['PlanDate'];
        $planDate = (array) $planDate;
        $planDate = $planDate[0];
        $planDate = \DateTime::createFromFormat('j M y', $planDate); 
        $planDate = $planDate->format('Y-m-d');

        $planTime = $first['PlanTime'];
        $planTime = (array) $planTime;
        $planTime = $planTime[0];

        $startTime = $planDate . " " . $planTime;

        $last = (array)$events[count((array)$events) - 1];
        $planDate = $last['PlanDate'];
        $planDate = (array) $planDate;
        $planDate = $planDate[0];
        $planDate = \DateTime::createFromFormat('j M y', $planDate); 
        $planDate = $planDate->format('Y-m-d');

        $planTime = $last['PlanTime'];
        $planTime = (array) $planTime;
        $planTime = $planTime[0];

        $endTime = $planDate . " " . $planTime;
        
        $duration = $last['PlanDuration'];
        $duration = (array) $duration;
        $duration = $this->timeToSeconds($duration[0]);

        $endTime = date('Y-m-d H:i:s', strtotime("+{$duration} SECONDS", strtotime($endTime)));

        $sm->get('TvGrabber\Model\Table\EpgModel')->deleteRecords(
            $startTime, $endTime, $channelNamespace, $serviceNamespace
        );

        while(list(, $node) = each($events)) {
            $planDate = $node->PlanDate;
            $planDate = (array) $planDate;
            $planDate = $planDate[0];
            $planDate = \DateTime::createFromFormat('j M y', $planDate); 
            $planDate = $planDate->format('Y-m-d');

            $planTime = $node->PlanTime;
            $planTime = (array) $planTime;
            $planTime = $planTime[0];

            $start = $planDate . " " . $planTime;

            $duration = $node->PlanDuration;
            $duration = (array) $duration;
            $duration = $this->timeToSeconds($duration[0]);

            $title = $node->TitleName;
            $title = (array) $title;
            $title = $title[0];

            $subTitle = $node->EpisodeName;
            $subTitle = (array) $subTitle;
            $subTitle = $subTitle[0];

            $text = $node->EPG;
            $text = (array) $text;
            $text = $text[0];

            $epg = new EpgEntity();
            $epg->epgId = new \Zend\Db\Sql\Predicate\Expression('UUID()');
            $epg->epgCrid = "";
            $epg->epgStreamUri = "";
            $epg->epgTitle = $title;
            $epg->epgSubTitle = $subTitle; 
            $epg->epgEpisode = "";
            $epg->epgCategory = "";
            $epg->epgIcon = "";
            $epg->epgText = $text;
            $epg->epgStart = $start;
            $epg->epgDuration = $duration;
            $epg->epgService = $serviceNamespace;
            $epg->epgServiceId = $serviceCode;
            $epg->epgChannel = $channelNamespace;
            $epg->epgChannelId = $channelCode;
            $epg->epgFile = $filePath;
            $epg->epgCreated = date('Y-m-d H:i:s');

            if($sm->get('TvGrabber\Model\Table\EpgModel')->saveRow($epg)) {

                $this->showStatus($counter, count((array)$events), " " . 
                    $channelNamespace . " - " . $channelCode);

                $sm->get('TvGrabber\Model\Table\EpgModel')
                   ->deleteOldRecords($channelNamespace, $serviceNamespace);

                $counter++;
            }
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
