<?php

namespace ModuleTest\XMLTV\Model\Entity;

use Zend\Test\PHPUnit\Model\AbstractModelTestCase;
use XMLTV\Model\Entity\LiveStream;

class LiveStreamTest extends AbstractModelTestCase
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
        $data = array (
            "id" => 1,
            "name" => "name",
            "epg_name" => "epg_name",
            "type" => "tv",
            "live_app_name" => "live_app_name",
            "moddate" => "0000-00-00 00:00:00",
            "modified_by" => 1,
            "company_id" => 1,
            "epg_url" => "epg_url",
            "status" => "status",
            "retention" => "retention",
            "epg_source" => "epg_source",
            "format_id" => "format_id",
            "epg_logo" => "epg_logo",
            "logo" => "logo",
            "primary_capture_site_id" => 1,
            "secondary_capture_site_id" => 1,
            "order" => 1,
            "online_status" => "online",
            "key" => "key",
            "genre" => "genre",
            "new_until" => "0000-00-00 00:00:00",
            "broadcast_from" => "00:00:00",
            "broadcast_to" => "00:00:00",
            "version" => "version"
        );

        $liveStreamModel = new LiveStream();
        //$liveStreamModel->exchangeArray($data);

        $keys = array_keys($data);
        $values = array_values($data);

        array_walk($keys, function(&$item, $key) {
            $item = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $item))));
        });
        
        $data = array_combine($keys, $values);

        $liveStreamModel->fromArray($data);

        $this->assertEquals($liveStreamModel->id, 1);
        $this->assertEquals($liveStreamModel->name, "name");
        $this->assertEquals($liveStreamModel->epgName, "epg_name");
        $this->assertEquals($liveStreamModel->type, "tv");
        $this->assertEquals($liveStreamModel->liveAppName, "live_app_name");
        $this->assertEquals($liveStreamModel->moddate, "0000-00-00 00:00:00");
        $this->assertEquals($liveStreamModel->modifiedBy, 1);
        $this->assertEquals($liveStreamModel->companyId, 1);
        $this->assertEquals($liveStreamModel->epgUrl, "epg_url");
        $this->assertEquals($liveStreamModel->status, "status");
        $this->assertEquals($liveStreamModel->retention, "retention");
        $this->assertEquals($liveStreamModel->epgSource, "epg_source");
        $this->assertEquals($liveStreamModel->formatId, "format_id");
        $this->assertEquals($liveStreamModel->epgLogo, "epg_logo");
        $this->assertEquals($liveStreamModel->logo, "logo");
        $this->assertEquals($liveStreamModel->primaryCaptureSiteId, 1);
        $this->assertEquals($liveStreamModel->secondaryCaptureSiteId, 1);
        $this->assertEquals($liveStreamModel->order, 1);
        $this->assertEquals($liveStreamModel->onlineStatus, "online");
        $this->assertEquals($liveStreamModel->key, "key");
        $this->assertEquals($liveStreamModel->genre, "genre");
        $this->assertEquals($liveStreamModel->newUntil, "0000-00-00 00:00:00");
        $this->assertEquals($liveStreamModel->broadcastFrom, "00:00:00");
        $this->assertEquals($liveStreamModel->broadcastTo, "00:00:00");
        $this->assertEquals($liveStreamModel->version, "version");


    }

    public function testGetArrayCopy()
    {
        $data = array (
            "id" => 1,
            "name" => "name",
            "epg_name" => "epg_name",
            "type" => "tv",
            "live_app_name" => "live_app_name",
            "moddate" => "0000-00-00 00:00:00",
            "modified_by" => 1,
            "company_id" => 1,
            "epg_url" => "epg_url",
            "status" => "status",
            "retention" => "retention",
            "epg_source" => "epg_source",
            "format_id" => "format_id",
            "epg_logo" => "epg_logo",
            "logo" => "logo",
            "primary_capture_site_id" => 1,
            "secondary_capture_site_id" => 1,
            "order" => 1,
            "online_status" => "online",
            "key" => "key",
            "genre" => "genre",
            "new_until" => "0000-00-00 00:00:00",
            "broadcast_from" => "00:00:00",
            "broadcast_to" => "00:00:00",
            "version" => "version"
        );

        $liveStreamModel = new LiveStream();
        $liveStreamModel->exchangeArray($data);

        $data = $liveStreamModel->getArrayCopy();

        foreach($data as $key => $value) {
            $this->assertEquals($liveStreamModel->$key, $value);
        }

    }

    public function testExchangeArray()
    {
        $data = array (
            "id" => 1,
            "name" => "name",
            "epg_name" => "epg_name",
            "type" => "tv",
            "live_app_name" => "live_app_name",
            "moddate" => "0000-00-00 00:00:00",
            "modified_by" => 1,
            "company_id" => 1,
            "epg_url" => "epg_url",
            "status" => "status",
            "retention" => "retention",
            "epg_source" => "epg_source",
            "format_id" => "format_id",
            "epg_logo" => "epg_logo",
            "logo" => "logo",
            "primary_capture_site_id" => 1,
            "secondary_capture_site_id" => 1,
            "order" => 1,
            "online_status" => "online",
            "key" => "key",
            "genre" => "genre",
            "new_until" => "0000-00-00 00:00:00",
            "broadcast_from" => "00:00:00",
            "broadcast_to" => "00:00:00",
            "version" => "version"
        );

        $liveStreamModel = new LiveStream();
        $liveStreamModel->exchangeArray($data);

        $this->assertEquals($liveStreamModel->id, 1);
        $this->assertEquals($liveStreamModel->name, "name");
        $this->assertEquals($liveStreamModel->epgName, "epg_name");
        $this->assertEquals($liveStreamModel->type, "tv");
        $this->assertEquals($liveStreamModel->liveAppName, "live_app_name");
        $this->assertEquals($liveStreamModel->moddate, "0000-00-00 00:00:00");
        $this->assertEquals($liveStreamModel->modifiedBy, 1);
        $this->assertEquals($liveStreamModel->companyId, 1);
        $this->assertEquals($liveStreamModel->epgUrl, "epg_url");
        $this->assertEquals($liveStreamModel->status, "status");
        $this->assertEquals($liveStreamModel->retention, "retention");
        $this->assertEquals($liveStreamModel->epgSource, "epg_source");
        $this->assertEquals($liveStreamModel->formatId, "format_id");
        $this->assertEquals($liveStreamModel->epgLogo, "epg_logo");
        $this->assertEquals($liveStreamModel->logo, "logo");
        $this->assertEquals($liveStreamModel->primaryCaptureSiteId, 1);
        $this->assertEquals($liveStreamModel->secondaryCaptureSiteId, 1);
        $this->assertEquals($liveStreamModel->order, 1);
        $this->assertEquals($liveStreamModel->onlineStatus, "online");
        $this->assertEquals($liveStreamModel->key, "key");
        $this->assertEquals($liveStreamModel->genre, "genre");
        $this->assertEquals($liveStreamModel->newUntil, "0000-00-00 00:00:00");
        $this->assertEquals($liveStreamModel->broadcastFrom, "00:00:00");
        $this->assertEquals($liveStreamModel->broadcastTo, "00:00:00");
        $this->assertEquals($liveStreamModel->version, "version");
    }
}
