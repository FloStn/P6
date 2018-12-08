<?php

namespace App\Tests\Entity;

use App\Entity\Image;
use App\Entity\Trick;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public function testGetNameReturnType()
    {
        $image = new Image();
        $name = 'name.jpg';
        $image->setName($name);
        $this->assertInternalType('string', $image->getName());
    }

    public function testSetNameReturnInstance()
    {
        $image = new Image();
        $name = 'name.jpg';
        $this->assertInstanceOf(Image::class, $image->setName($name));
    }

    public function testGetFileReturnType()
    {
        $image = new Image();
        $file = array('test_file.jpg');
        $image->setFile($file);
        $this->assertInternalType('array', $image->getFile());
    }

    public function testSetFileReturnInstance()
    {
        $image = new Image();
        $file = array('test_file.jpg');
        $this->assertInstanceOf(Image::class, $image->setFile($file));
    }

    public function testGetTrickReturnInstance()
    {
        $image = new Image();
        $trick = new Trick();
        $image->setTrick($trick);
        $this->assertInstanceOf(Trick::class, $image->getTrick());
    }

    public function testSetTrickReturnInstance()
    {
        $image = new Image();
        $trick = new Trick();
        $this->assertInstanceOf(Image::class, $image->setTrick($trick));
    }
}