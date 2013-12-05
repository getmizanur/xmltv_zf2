<?php

namespace ModuleTest\TvGrabber\Model\Entity;

use Zend\Test\PHPUnit\Model\AbstractModelTestCase;
use TvGrabber\Model\Entity\Epg;

class EpgTest extends AbstractModelTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../../config/application.config.php'
        );
        parent::setUp();
    }

    public function testFromArray()
    {
        $data = array(
            'epgKeyId' => 1,
            'epgId' => "id",
            'epgCrid' => "crid",
            'epgStreamUri' => "stream_uri",
            'epgTitle' => "title",
            'epgSubTitle' => "sub_title",
            'epgEpisode' => "episode",
            'epgIcon' => "icon",
            'epgText' => "text",
            'epgStart' => '2013-11-24 11:00:00',
            'epgDuration' => 1000,
            'epgService' => "test",
            'epgServiceId' => 1,
            'epgChannel' => "test",
            'epgChannelId' => 1,
            'epgCustomGeneratedVodCount' => 1
        );
        $epgModel = new Epg();
        $epgModel->fromArray($data);
         
        $this->assertEquals(1, $epgModel->epgKeyId);
        $this->assertTrue(is_numeric($epgModel->epgKeyId));
        $this->assertEquals("id", $epgModel->epgId);
        $this->assertEquals("crid", $epgModel->epgCrid);
        $this->assertEquals("stream_uri", $epgModel->epgStreamUri);
        $this->assertEquals("title", $epgModel->epgTitle);
        $this->assertEquals("sub_title", $epgModel->epgSubTitle);
        $this->assertEquals("episode", $epgModel->epgEpisode);
        $this->assertEquals("icon", $epgModel->epgIcon);
        $this->assertEquals("text", $epgModel->epgText);
        $this->assertEquals("2013-11-24 11:00:00", $epgModel->epgStart);
        $this->assertEquals("1000", $epgModel->epgDuration);
        $this->assertTrue(is_int($epgModel->epgDuration));
        $this->assertEquals("test", $epgModel->epgService);
        $this->assertEquals("1", $epgModel->epgServiceId);
        $this->assertTrue(is_int($epgModel->epgServiceId));
        $this->assertEquals("test", $epgModel->epgChannel);
        $this->assertEquals("1", $epgModel->epgChannelId);
        $this->assertTrue(is_int($epgModel->epgChannelId));
        $this->assertTrue(is_int($epgModel->epgCustomGeneratedVodCount));
    }

    public function testGetArrayCopy()
    {
        $data = array(
            'epg_key_id' => 1,
            'epg_id' => "id",
            'epg_crid' => "crid",
            'epg_stream_uri' => "stream_uri",
            'epg_title' => "title",
            'epg_sub_title' => "sub_title",
            'epg_episode' => "episode",
            'epg_icon' => "icon",
            'epg_text' => "text",
            'epg_start' => '2013-11-24 11:00:00',
            'epg_duration' => 1000,
            'epg_service' => "test",
            'epg_service_id' => 1,
            'epg_channel' => "test",
            'epg_channel_id' => 1,
            'epg_custom_generated_vod_count' => 1
        );
        $epgModel = new Epg();
        $epgModel->exchangeArray($data);

        $values = $epgModel->getArrayCopy();
        
        $this->assertEquals(1, $values['epgKeyId']);
        $this->assertTrue(is_numeric($values['epgKeyId']));
        $this->assertEquals("id", $values['epgId']);
        $this->assertEquals("crid", $values['epgCrid']);
        $this->assertEquals("stream_uri", $values['epgStreamUri']);
        $this->assertEquals("title", $values['epgTitle']);
        $this->assertEquals("sub_title", $values['epgSubTitle']);
        $this->assertEquals("episode", $values['epgEpisode']);
        $this->assertEquals("icon", $values['epgIcon']);
        $this->assertEquals("text", $values['epgText']);
        $this->assertEquals("2013-11-24 11:00:00", $values['epgStart']);
        $this->assertEquals("1000", $values['epgDuration']);
        $this->assertTrue(is_int($values['epgDuration']));
        $this->assertEquals("test", $values['epgService']);
        $this->assertEquals("1", $values['epgServiceId']);
        $this->assertTrue(is_int($values['epgServiceId']));
        $this->assertEquals("test", $values['epgChannel']);
        $this->assertEquals("1", $values['epgChannelId']);
        $this->assertTrue(is_int($values['epgChannelId']));
        $this->assertTrue(is_int($values['epgCustomGeneratedVodCount']));
    }

    public function testExchangeArray()
    {
        $data = array(
            'epg_key_id' => 1,
            'epg_id' => "id",
            'epg_crid' => "crid",
            'epg_stream_uri' => "stream_uri",
            'epg_title' => "title",
            'epg_sub_title' => "sub_title",
            'epg_episode' => "episode",
            'epg_icon' => "icon",
            'epg_text' => "text",
            'epg_start' => '2013-11-24 11:00:00',
            'epg_duration' => 1000,
            'epg_service' => "test",
            'epg_service_id' => 1,
            'epg_channel' => "test",
            'epg_channel_id' => 1,
            'epg_custom_generated_vod_count' => 1
        );
        $epgModel = new Epg();
        $epgModel->exchangeArray($data);

        $this->assertEquals(1, $epgModel->epgKeyId);
        $this->assertTrue(is_numeric($epgModel->epgKeyId));
        $this->assertEquals("id", $epgModel->epgId);
        $this->assertEquals("crid", $epgModel->epgCrid);
        $this->assertEquals("stream_uri", $epgModel->epgStreamUri);
        $this->assertEquals("title", $epgModel->epgTitle);
        $this->assertEquals("sub_title", $epgModel->epgSubTitle);
        $this->assertEquals("episode", $epgModel->epgEpisode);
        $this->assertEquals("icon", $epgModel->epgIcon);
        $this->assertEquals("text", $epgModel->epgText);
        $this->assertEquals("2013-11-24 11:00:00", $epgModel->epgStart);
        $this->assertEquals("1000", $epgModel->epgDuration);
        $this->assertTrue(is_int($epgModel->epgDuration));
        $this->assertEquals("test", $epgModel->epgService);
        $this->assertEquals("1", $epgModel->epgServiceId);
        $this->assertTrue(is_int($epgModel->epgServiceId));
        $this->assertEquals("test", $epgModel->epgChannel);
        $this->assertEquals("1", $epgModel->epgChannelId);
        $this->assertTrue(is_int($epgModel->epgChannelId));
        $this->assertTrue(is_int($epgModel->epgCustomGeneratedVodCount));
    } 
}
