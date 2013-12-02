<?php

namespace ModuleTest\XMLTV\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\InputFilter\Factory;
use XMLTV\Model\Entity\Epg as EpgEntity;
use XMLTV\Util\DbUtil;

class TvPlayerControllerTest extends AbstractHttpControllerTestCase
{    
    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../config/application.config.php'
        );
        parent::setUp();
    }

    public function testFailureTransaction()
    {
        $sm = $this->getApplicationServiceLocator();

        $liveStreams = $sm->get('LiveStreamsModel')
            ->getLiveStreamsByCompanyId(46);

        $map = array();
        foreach($liveStreams as $key => $row) {
            $map[$key] = $row->epgName;
        }

        $xml = simplexml_load_file(__DIR__ . '/../../../../data/ebs/SimpleStream.xml');
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

                $sm->get('EpgModel')->deleteRecord(
                    $startTime, $stopTime, $displayname, 'tvplayer'
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
                    $epg->epgIcon = $icon;
                    $epg->epgText = $text;
                    $epg->epgStart = $start;
                    $epg->epgDuration = $duration;
                    $epg->epgService = 'tvplayer';
                    $epg->epgServiceId = 46;
                    $epg->epgChannel = $displayname;
                    $epg->epgChannelId = array_search($displayname, $map);
                    $epg->epgFile = '';
                    $epg->epgCreated = date('Y-m-d H:i:s');

                     //var_dump($sm->get('EpgModel')->saveRow($epg));


                    if($sm->get('EpgModel')->insertRow($epg)) {
                        $this->assertTrue(true);
                        //DbUtil::showStatus($counter, count($programmes), " " . $displayname . " - " . array_search($displayname, $map));

                        //$sqlDelete = "
                            //DELETE FROM
                                //epg
                            //WHERE
                                //DATE(epg_start) < (NOW() - INTERVAL 9 DAY) AND
                                //epg_service = :epg_service AND epg_channel = :epg_channel
                        //";
                        //$params = array (
                            //':epg_service' => 'tvplayer',
                            //':epg_channel' => $displayname
                        //);

                        //$this->Execute($sqlDelete, $params);
                    }else{
                        $this->assertTrue(false);
                    }

                    $counter++;
                }
            }
        }
    }
}
