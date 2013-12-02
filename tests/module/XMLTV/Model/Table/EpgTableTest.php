<?php

namespace ModuleTest\XMLTV\Model\Table;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Test\PHPUnit\Model\AbstractModelTestCase;
use XMLTV\Model\Entity\Epg;
use XMLTV\Model\Table\EpgTable;

class EpgTableTest extends AbstractModelTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../../config/application.config.php'
        );
        parent::setUp();
    }

    public function testDeleteRecord()
    {
        $sm = $this->getApplicationServiceLocator();
        $model = $sm->get('EpgModel');

        $model->deleteRecord(date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), '4music', 'boxtv');
    }


    public function testGetEpgByKeyId()
    {
        $sm = $this->getApplicationServiceLocator();
        $model = $sm->get('EpgModel');

        //$result = $model->getEpgByKeyId(16739);

        //$this->assertEquals($result->epgKeyId, 16739);
    }
}
