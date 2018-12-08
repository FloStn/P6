<?php

namespace App\Tests\Entity;

use App\Entity\Video;
use App\Entity\Trick;
use PHPUnit\Framework\TestCase;

class VideoTest extends TestCase
{
    public function testGetIframeUrlReturnType()
    {
        $video = new Video();
        $iframeUrl = 'http://wwww.youtube.com';
        $video->setIframeUrl($iframeUrl);
        $this->assertInternalType('string', $video->getIframeUrl());
    }

    public function testSetIframeUrlReturnInstance()
    {
        $video = new Video();
        $iframeUrl = 'http://www.youtube.com';
        $this->assertInstanceOf(Video::class, $video->setIframeUrl($iframeUrl));
    }

    public function testGetTrickReturnInstance()
    {
        $video = new Video();
        $trick = new Trick();
        $video->setTrick($trick);
        $this->assertInstanceOf(Trick::class, $video->getTrick());
    }

    public function testSetTrickReturnInstance()
    {
        $video = new Video();
        $trick = new Trick();
        $this->assertInstanceOf(Video::class, $video->setTrick($trick));
    }
}