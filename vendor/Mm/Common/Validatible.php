<?php
namespace Mm\Common;

use Zend\InputFilter\InputFilterAwareInterface;

interface Validatible extends InputFilterAwareInterface
{
    public function isValid();
}
