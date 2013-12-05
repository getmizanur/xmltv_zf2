<?php
/**
 * Mizanur Rahman
 *
 * @link      https://www.linkedin.com/pub/mizanur-rahman/32/b15/248
 * @copyright N/A
 */

namespace TvGrabber\Model\Service;

use TvGrabber\Model\Entity\Epg as EpgEntity;
use TvGrabber\Model\Table\EpgTable;

class XmltvService extends AbstractService
{
    public function processXml($companyCode, $companyNamespace) 
    {        
        $sm = $this->getServiceLocator(); 

        $liveStreams = $sm->get('LiveStreamsModel')
            ->getLiveStreamsByCompanyId($companyCode);

        $map = array();
        foreach($liveStreams as $key => $row) {
            $map[$key] = $row->epgName;
        }
        
        $xml = simplexml_load_file(__DIR__ . '/../../../../../../data/ebs/SimpleStream.xml');
        $channels = $xml->xpath('/tv/channel');
        while(list(, $node) = each($channels)) {
            $obj = $node->xpath('@id');
            $channelId = (string)$obj[0]->id;

            $obj = $node->xpath('display-name');
            $displayname = (string)$obj[0];

            $obj = $node->xpath('icon/@src');
            $icon = (string)$obj[0]->src;
             
            if(array_search($displayname, $map)) {
                $programmes = $xml->xpath('/tv/programme[@channel="' .$channelId . '"]');

                $obj = $programmes[0]->xpath('@start');
                $startTime = (string)$obj[0]->start;
                $date = \DateTime::createFromFormat('YmdHi', $startTime);
                $startTime = $date->format('Y-m-d H:i');

                $obj = $programmes[count($programmes) - 1]->xpath('@stop');
                $stopTime = (string)$obj[0]->stop;
                $date = \DateTime::createFromFormat('YmdHi', $stopTime);
                $stopTime = $date->format('Y-m-d H:i');

                $sm->get('EpgModel')->deleteRecords(
                    $startTime, $stopTime, $displayname, $companyNamespace
                );

                $counter = 1;
                foreach($programmes as $programme) {
                    $obj = $programme->xpath('@start');
                    $start = (string)$obj[0]->start;
                    $date = \DateTime::createFromFormat('YmdHi', $start);
                    $start = $date->format('Y-m-d H:i');

                    $obj = $programme->xpath('@stop');
                    $stop = (string)$obj[0]->stop;
                    $date = \DateTime::createFromFormat('YmdHi', $stop);
                    $stop = $date->format('Y-m-d H:i');

                    $obj = (array) $programme->title;
                    $title = $obj[0];
                    $obj = (array) $programme->{'sub-title'};
                    $subTitle = (isset($obj) && isset($obj[0])) ? $obj[0] : "";
                    $obj = (array)$programme->desc;
                    $text = (isset($obj) && isset($obj[0])) ? $obj[0] : "";

                    $obj = (array) $programme->{'episode-num'};
                    $episodeNum = (isset($obj) && isset($obj[0])) ? $obj[0] : "";

                    $obj = (array) $programme->{'category'};
                    $category = (isset($obj) && isset($obj[0])) ? $obj[0] : "";

                    $obj = (array) $programme->icon;

                    $startDateTime = strtotime($start);
                    $stopDateTime = strtotime($stop);

                    $duration = $stopDateTime - $startDateTime;

                    $obj = $programme->xpath('icon/@src');
                    $icon = (isset($obj) && isset($obj[0])) ? (string)$obj[0]->src : "";

                    $epg = new EpgEntity();
                    $epg->epgId = new \Zend\Db\Sql\Predicate\Expression('UUID()');
                    $epg->epgCrid = "";
                    $epg->epgStreamUri = "";
                    $epg->epgTitle = $title;
                    $epg->epgSubTitle = $subTitle; 
                    $epg->epgEpisode = $episodeNum;
                    $epg->epgCategory = $category;
                    $epg->epgIcon = $icon;
                    $epg->epgText = $text;
                    $epg->epgStart = $start;
                    $epg->epgDuration = $duration;
                    $epg->epgService = $companyNamespace;
                    $epg->epgServiceId = $companyCode;
                    $epg->epgChannel = $displayname;
                    $epg->epgChannelId = array_search($displayname, $map);
                    $epg->epgFile = '';
                    $epg->epgCreated = date('Y-m-d H:i:s');

                    if($sm->get('EpgModel')->saveRow($epg)) {
                        $this->showStatus($counter, count($programmes), " " . 
                            $displayname . " - " . array_search($displayname, $map));

                        $sm->get('EpgModel')
                           ->deleteOldRecords($displayname, $companyNamespace);
                    }

                    $counter++;
                }
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
}
