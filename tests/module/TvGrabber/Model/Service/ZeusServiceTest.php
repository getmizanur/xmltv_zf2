<?php

namespace ModuleTest\TvGrabber\Model\Service;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use TvGrabber\Model\Entity\Epg as EpgEntity;
use TvGrabber\Model\Entity\File as FileEntity;
use TvGrabber\Model\Table\EpgTable;


class ZeusServiceTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../../config/application.config.php'
        );
        parent::setUp();
    }

    public function testProcessXml()
    {
        $sm = $this->getApplicationServiceLocator();
        $serviceCode = 37;
        $serviceNamespace = 'boxnation';
        $channelCode = 63;
        $channelNamespace = 'Box Nation';
       
        $files = $this->scanDirectory('/home/mizanur/public_html/xmltv/tests/data/bn');
 
        foreach($files as $file) {
            $xml = simplexml_load_file('/home/mizanur/public_html/xmltv/tests/data/bn/' . $file);
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

            $sm->get('EpgModel')->deleteRecords(
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
                $epg->epgFile = '/home/mizanur/public_html/xmltv/tests/data/bn/' . $file;
                $epg->epgCreated = date('Y-m-d H:i:s');

                //if($sm->get('EpgModel')->saveRow($epg)) {

                    //var_dump('/home/mizanur/public_html/xmltv/tests/data/bn/' . $file);
                    //$this->showStatus($counter, count((array)$events), " " . 
                        //$channelNamespace . " - " . $channelCode);

                    //$sm->get('EpgModel')
                       //->deleteOldRecords($channelNamespace, $serviceNamespace);

                    //$counter++;
                //}
            }
        }
    }

    public function timeToSeconds($string)
    {
        $t = explode(':', $string);
        return $t[0] * 60  + $t[1];
    }
 
    public function ConvIso8601DurationToTime($interval)
    {
        $h = $interval->h * 60 * 60;
        $m = $interval->i * 60;
        $s = $interval->s;

        return ($h + $m + $s);
    }

    public function scanDirectory($outerDir)
    { 
        $dirs = array_diff(scandir($outerDir), array( ".", ".." )); 
        $dir_array = array(); 
        foreach( $dirs as $d ){ 
            if( is_dir($outerDir."/".$d) ) 
                $dir_array[$d] = ScanDirectory($outerDir."/".$d);
            else 
                $dir_array[$d] = $d; 
        } 
        return $dir_array; 
    }
 
}
