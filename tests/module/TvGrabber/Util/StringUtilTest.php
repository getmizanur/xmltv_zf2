<?php

namespace ModuleTest\TvGrabber\Util;

use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_ExpectationFailedException;

use Mm\Util\StringUtil;

class StringUtilTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testDecamelize()
    {
        $string = "LongString";
        
        $string = StringUtil::decamelize($string, '_');

        $this->assertEquals($string, 'long_string');
    }
}

