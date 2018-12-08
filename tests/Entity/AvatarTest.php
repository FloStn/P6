<?php

namespace App\Tests\Entity;

use App\Entity\Avatar;
use PHPUnit\Framework\TestCase;

class AvatarTest extends TestCase
{
    public function testSetUrlReturnInstance()
    {
        $avatar = new Avatar();
        $url = 'test_avatar.png';
        $this->assertInstanceOf(Avatar::class, $avatar->setUrl($url));
    }

    public function testGetUrlReturnType()
    {
        $avatar = new Avatar();
        $url = 'test_avatar.png';
        $this->assertInternalType('string', $avatar->getUrl());
    }
}