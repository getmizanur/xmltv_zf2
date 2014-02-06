<?php

namespace ModuleTest\Epg\Model\Table;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Test\PHPUnit\Model\AbstractModelTestCase;
use Epg\Model\Entity\Epg;

class EpgTableTest extends AbstractModelTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../../config/application.config.php'
        );
        parent::setUp();
    }

    public function testGetEpgChannels()
    {
        $sm = $this->getApplicationServiceLocator();
        $model = $sm->get('Epg\Model\Table\EpgModel');

        $rows = $model->getEpgChannels('tvplayer');
        
          
        
    }


    public function testDeleteRecord()
    {
        $sm = $this->getApplicationServiceLocator();
        $model = $sm->get('Epg\Model\Table\EpgModel');

    }

    public function testGetEpgByService()
    {
        $sm = $this->getApplicationServiceLocator();
        $model = $sm->get('Epg\Model\Table\EpgModel');

        $result = $model->getEpgByKeyIds(16739);

        //$this->assertEquals($result->epgKeyId, 16739);
    }
}
