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

class TvAnytimeService extends AbstractService
{
    public function processXml($serviceCode, $serviceNamespace, 
        $channelCode, $channelNamespace, $filePath) 
    {  
        if(!file_exists($filePath)) {
            return false;
        }

        $sm = $this->getServiceLocator(); 

        $xml = simplexml_load_file($filePath);

        $xmlns = $xml->getNamespaces();
        $xmlns = $xmlns[""];

        $xml->registerXPathNamespace('xmlns', $xmlns);
        $schedule = $xml->xpath('//xmlns:Schedule');

        $startTime = null; $endTime = null;
        array_walk($schedule[0]->attributes(), function($item) use(&$startTime, &$endTime) {
            $startTime = $item['start'];
            $endTime = $item['end'];
        });

        $sm->get('EpgModel')->deleteRecords(
            $startTime, $endTime, $channelNamespace, $serviceNamespace
        );

        $scheduleEvents = $xml->xpath('//xmlns:ScheduleEvent');

        $counter = 1;
        while(list(, $node) = each($scheduleEvents)) {
            $crid = null;
            array_walk($node->Program->attributes(), function($item, $key) use (&$crid) {
                $crid = $item['crid'];
            });
            $publishedStartTime = $node->PublishedStartTime;
            if(0 < count((array)$publishedStartTime)) {
                $publishedStartTime = (array) $publishedStartTime;
                $publishedStartTime = $publishedStartTime[0];
            }
            $publishedDuration = $node->PublishedDuration;
            $interval = new \DateInterval($publishedDuration);
            $publishedDuration = $this->ConvIso8601DurationToTime($interval);

            $xml->registerXPathNamespace('xmlns', $xmlns);
            $programInformation = $xml->xpath('//xmlns:ProgramInformation');

            while(list(, $node) = each($programInformation)) {
                $programId = null;
                array_walk($node->attributes(), function($item) use (&$programId) {
                    $programId = $item['programId'];
                });

                if($crid === $programId) {
                    $title = null; $subTitle = null;
                    $title = $node->BasicDescription->Title;
                    if(1 < count((array)$title)) {
                        $subTitle = html_entity_decode($title[1]);
                        $title = html_entity_decode($title[0]);
                    }else{
                        $title = html_entity_decode($title[0]);
                    }

                    $synopsis = $node->BasicDescription->Synopsis;
                    $synopsis = html_entity_decode($synopsis[0]);

                    $seasonNumber = $node->BasicDescription->SeasonNumber;
                    if(count((array)$seasonNumber)){
                        $seasonNumber = "S" . $seasonNumber[0];
                    }else{
                        $seasonNumber = NULL;
                    }
                        
                    $episodeNumber = $node->BasicDescription->EpisodeNumber;
                    if(count((array)$episodeNumber)){
                        $episodeNumber = "E" . $episodeNumber[0];
                    }else{
                        $episodeNumber = NULL;
                    }

                    $genre = $node->BasicDescription->Genre;
                    if(count((array)$genre)){
                        $genre = (array)$genre->Name;
                        $genre = $genre[0];
                    }else{
                        $genre = NULL;
                    }

                    $epg = new EpgEntity();
                    $epg->epgId = new \Zend\Db\Sql\Predicate\Expression('UUID()');
                    $epg->epgCrid = $crid;
                    $epg->epgStreamUri = "";
                    $epg->epgTitle = $title;
                    $epg->epgSubTitle = $subTitle; 
                    $epg->epgEpisode = $seasonNumber . $episodeNumber;
                    $epg->epgCategory = $genre;
                    $epg->epgIcon = "";
                    $epg->epgText = $synopsis;
                    $epg->epgStart = $publishedStartTime;
                    $epg->epgDuration = $publishedDuration;
                    $epg->epgService = $serviceNamespace;
                    $epg->epgServiceId = $serviceCode;
                    $epg->epgChannel = $channelNamespace;
                    $epg->epgChannelId = $channelCode;
                    $epg->epgFile = $filePath;
                    $epg->epgCreated = date('Y-m-d H:i:s');

                    if($sm->get('EpgModel')->saveRow($epg)) {
                        $this->showStatus($counter, count((array)$scheduleEvents), " " . 
                            $channelNamespace . " - " . basename($filePath));

                        $sm->get('EpgModel')
                           ->deleteOldRecords($channelNamespace, $serviceNamespace);
                        
                        $counter++;
                    }
                }
            }
        }

        if($counter >= count((array)$scheduleEvents) + 1) {
            $file = $sm->get('FileModel')->getFileByHash(
                sha1_file($filePath)
            );

            $file->fileProcessed = true;
            $sm->get('FileModel')->saveRow($file);
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

        if(preg_match('/xml$/', $filePath) && file_exists($filePath)) {
            $row = $sm->get('FileModel')->getFileByHash(
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

                $sm->get('FileModel')->saveRow($file);
            }
        }
    }
}
