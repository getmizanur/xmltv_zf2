<?php
namespace ModuleTest\TvGrabber\Model\Service;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use TvGrabber\Model\Entity\Epg as EpgEntity;

class TvAnytimeServiceTest extends AbstractHttpControllerTestCase
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

        $files = $this->scanDirectory('/home/mizanur/public_html/xmltv/tests/data/hc');

        foreach($files as $file) {
            $fileUri = '/home/mizanur/public_html/xmltv/tests/data/hc/' . $file;
            $xml = simplexml_load_file($fileUri);

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
                $startTime, $endTime, 'Horse and Country', 'horseandcountry'
            );

            $scheduleEvents = $xml->xpath('//xmlns:ScheduleEvent');

            while(list(, $node) = each($scheduleEvents)) {
                $crid = null;
                array_walk($node->Program->attributes(), function($item, $key) use (&$crid) {
                    $crid = $item['crid'];
                });
                $publishedStartTime = $node->PublishedStartTime;
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
                            $genre = $genre;
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
                        $epg->epgService = 'horseandcountry';
                        $epg->epgServiceId = 25;
                        $epg->epgChannel = 'Horse and Country';
                        $epg->epgChannelId = 164;
                        $epg->epgFile = $fileUri;
                        $epg->epgCreated = date('Y-m-d H:i:s');

                        //if($sm->get('EpgModel')->saveRow($epg)) {
                            //var_dump($fileUri);
                        //}

                        //var_dump($basicDescription);
                        //die();
                        //array_walk($basicDescription, function($item) use (&$title, &$synopsis) {
                            //$values = (array)$item;
                            //var_dump($item);
                        //});
                        //die();
                    }
                }
            }
        }
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
