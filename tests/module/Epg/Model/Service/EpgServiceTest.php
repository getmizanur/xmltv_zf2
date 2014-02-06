<?php

namespace ModuleTest\Epg\Model\Service;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class EpgServiceTest extends AbstractHttpControllerTestCase
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
        $service = $sm->get('TvGrabber\Model\Service\XmltvService');
    }
 
}
