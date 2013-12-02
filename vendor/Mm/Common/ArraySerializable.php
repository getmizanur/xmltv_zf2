<?php
namespace Mm\Common;

interface ArraySerializable
{
    public function getArrayCopy();
    public function fromArray($data);
}
