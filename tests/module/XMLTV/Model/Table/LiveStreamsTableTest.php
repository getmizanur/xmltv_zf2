<?php

namespace ModuleTest\XMLTV\Model\Table;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Test\PHPUnit\Model\AbstractModelTestCase;
use XMLTV\Model\Entity\Epg;
use XMLTV\Model\Table\EpgTable;

class LiveStreamsTableTest extends AbstractModelTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../../config/application.config.php'
        );
        parent::setUp();
    }

    public function testGetLiveStreamsByCompanyId()
    {
        $sm = $this->getApplicationServiceLocator();
        $model = $sm->get('LiveStreamsModel');

        $result = $model->getLiveStreamsByCompanyId(46);

        $this->assertEquals($result[156]->companyId, 46);  
    }

}
