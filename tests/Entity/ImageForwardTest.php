<?php

namespace App\Tests\Entity;

use App\Entity\ImageForward;
use App\Entity\Trick;
use PHPUnit\Framework\TestCase;

class ImageForwardTest extends TestCase
{
    public function testGetFileNameReturnType()
    {
        $imageForward = new ImageForward();
        $fileName = 'test_file_name.jpg';
        $imageForward->setFileName($fileName);
        $this->assertInternalType('string', $imageForward->getFileName());
    }

    public function testSetFileNameReturnInstance()
    {
        $imageForward = new ImageForward();
        $fileName = 'test_file_name.jpg';
        $this->assertInstanceOf(ImageForward::class, $imageForward->setFileName($fileName));
    }

    public function testGetTrickReturnInstance()
    {
        $imageForward = new ImageForward();
        $trick = new Trick();
        $imageForward->setTrick($trick);
        $this->assertInstanceOf(Trick::class, $imageForward->getTrick());
    }

    public function testSetTrickReturnInstance()
    {
        $imageForward = new ImageForward();
        $trick = new Trick();
        $this->assertInstanceOf(ImageForward::class, $imageForward->setTrick($trick));
    }

    public function testGetFileReturnType()
    {
        $imageForward = new ImageForward();
        $file = array('test_file.jpg');
        $imageForward->setFile($file);
        $this->assertInternalType('array', $imageForward->getFile());
    }

    public function testSetFileReturnInstance()
    {
        $imageForward = new ImageForward();
        $file = array('test_file.jpg');
        $this->assertInstanceOf(ImageForward::class, $imageForward->setFile($file));
    }
}