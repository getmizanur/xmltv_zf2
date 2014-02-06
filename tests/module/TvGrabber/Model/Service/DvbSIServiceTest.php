<?php
namespace ModuleTest\TvGrabber\Model\Service;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use TvGrabber\Model\Entity\Epg as EpgEntity;

class DvbSIServiceTest extends AbstractHttpControllerTestCase
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

        $serviceCode = 29;
        $serviceNamespace = 'vintagetv';
        $channelCode = 161;
        $channelNamespace = 'Vintage TV';

        $files = $this->scanDirectory('/home/mizanur/public_html/xmltv/tests/data/vn');

        foreach($files as $file) {
            $xml = simplexml_load_file('/home/mizanur/public_html/xmltv/tests/data/vn/' . $file);

            $obj = $xml->xpath('/DVBSchedule/Schedule/TimePeriod/@startTime');
            $startTime = $obj[0]->startTime;

            $obj = $xml->xpath('/DVBSchedule/Schedule/TimePeriod/@endTime');
            $endTime = $obj[0]->endTime;

            //$sm->get('TvGrabber\Model\Table\EpgModel')->deleteRecords(
                //$startTime, $endTime, $channelNamespace, $serviceNamespace
            //);


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
                $epg->epgFile = '/home/mizanur/public_html/xmltv/tests/data/vn/' . $file;
                $epg->epgCreated = date('Y-m-d H:i:s');

                //if($sm->get('TvGrabber\Model\Table\EpgModel')->saveRow($epg)) {

                    //var_dump('/home/mizanur/public_html/xmltv/tests/data/vn/' . $file);
                    //$this->showStatus($counter, count((array)$events), " " . 
                        //$channelNamespace . " - " . $channelCode);

                    //$sm->get('TvGrabber\Model\Table\EpgModel')
                       //->deleteOldRecords($channelNamespace, $serviceNamespace);

                    //$counter++;
                //}
            }
        }
    }

    public function convIso8601DurationToTime($interval)
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
