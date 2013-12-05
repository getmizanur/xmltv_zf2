<?php

namespace ModuleTest\TvGrabber\Model\Table;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Test\PHPUnit\Model\AbstractModelTestCase;
use TvGrabber\Model\Entity\Epg;

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
