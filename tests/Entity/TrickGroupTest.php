<?php

namespace App\Tests\Entity;

use App\Entity\TrickGroup;
use PHPUnit\Framework\TestCase;

class TrickGroupTest extends TestCase
{
    public function testGetNameReturnType()
    {
        $trickGroup = new TrickGroup();
        $name = 'Group Test';
        $trickGroup->setName($name);
        $this->assertInternalType('string', $trickGroup->getName());
    }

    public function testSetNameReturnInstance()
    {
        $trickGroup = new TrickGroup();
        $name = 'Groupe Test';
        $this->assertInstanceOf(TrickGroup:: class, $trickGroup->setName($name));
    }
}