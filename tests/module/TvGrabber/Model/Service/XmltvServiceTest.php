<?php

namespace ModuleTest\TvGrabber\Model\Service;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class XmltvServiceTest extends AbstractHttpControllerTestCase
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
        $service = $sm->get('XmltvService');

        //$service->processXml();
    }
 
}
