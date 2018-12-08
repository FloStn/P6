<?php

namespace App\Tests\Entity;

use App\Entity\Trick;
use App\Entity\TrickGroup;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Video;
use App\Entity\ImageForward;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class TrickTest extends TestCase
{
   public function testGetNameReturnType()
   {
       $trick = new Trick();
       $name = 'Test';
       $trick->setName($name);
       $this->assertInternalType('string', $trick->getName());
   }

   public function testSetNameReturnInstance()
   {
       $trick = new Trick();
       $name = 'Test';
       $this->assertInstanceOf(Trick::class, $trick->setName($name));
   }

   public function testNewSlugReturnValueAndType()
   {
       $trick = new Trick();
       $slug = $trick->newSlug('Test Slug');
       $this->assertSame('test-slug', $slug);
       $this->assertInternalType('string', $slug);
   }

   public function testNewSlugEmptyParam()
   {
       $trick = new Trick();
       $slug = $trick->newSlug('');
       $this->assertSame('n-a', $slug);
   }

   public function testGetSlugReturnType()
   {
       $trick = new Trick();
       $slug = $trick->newSlug('Test');
       $trick->setSlug($slug);
       $this->assertInternalType('string', $trick->getSlug());
   }

   public function testSetSlugReturnInstance()
   {
       $trick = new Trick();
       $slug = $trick->newSlug('Test');
       $this->assertInstanceOf(Trick::class, $trick->setSlug($slug));
   }

   public function testGetDescriptionReturnType()
   {
       $trick = new Trick();
       $description = 'Description de test d\'une figure';
       $trick->setDescription($description);
       $this->assertInternalType('string', $trick->getDescription());
   }

   public function testSetDescriptionReturnInstance()
   {
       $trick = new Trick();
       $description = 'Description de test d\'une figure';
       $this->assertInstanceOf(Trick::class, $trick->setDescription($description));
   }

   public function testGetPublishDateReturnInstance()
   {
       $trick = new Trick();
       $this->assertInstanceOf(\Datetime::class, $trick->getPublishDate());
   }

   public function testSetPublishDateReturnInstance()
   {
        $trick = new Trick();
        $datetime = new \Datetime();
        $this->assertInstanceOf(Trick::class, $trick->setPublishDate($datetime));
   }

   public function testGetEditDateReturnInstance()
   {
       $trick = new Trick();
       $datetime = new \Datetime();
       $trick->setEditDate($datetime);
       $this->assertInstanceOf(\Datetime::class, $trick->getEditDate());
   }

   public function testSetEditDateReturnInstance()
   {
       $trick = new Trick();
       $datetime = new \Datetime();
       $this->assertInstanceOf(Trick::class, $trick->setEditDate($datetime));
   }

   public function testGetTrickGroupReturnInstance()
   {
       $trick = new Trick();
       $group = new TrickGroup();
       $trick->setTrickGroup($group);
       $this->assertInstanceOf(TrickGroup::class, $trick->getTrickGroup());
   }

   public function testSetTrickGroupReturnInstance()
   {
       $trick = new Trick();
       $group = new TrickGroup();
       $this->assertInstanceOf(Trick::class, $trick->setTrickGroup($group));
   }

   public function testGetAuthorReturnInstance()
   {
       $trick = new Trick();
       $author = new User();
       $trick->setAuthor($author);
       $this->assertInstanceOf(User::class, $trick->getAuthor());
   }

   public function testSetAuthorReturnInstance()
   {
       $trick = new Trick();
       $author = new User();
       $this->assertInstanceOf(Trick::class, $trick->setAuthor($author));
   }

   public function testGetCommentsReturnInstance()
   {
       $trick = new Trick();
       $comment1 = new Comment();
       $comment2 = new Comment();
       $comment1->setTrick($trick);
       $comment2->setTrick($trick);
       $this->assertInstanceOf(Collection::class, $trick->getComments());
   }

   public function testGetImagesReturnInstance()
   {
       $trick = new Trick();
       $image1 = new Image();
       $image2 = new Image();
       $image1->setTrick($trick);
       $image2->setTrick($trick);
       $this->assertInstanceOf(Collection::class, $trick->getImages());
   }

   public function testAddImageReturnInstance()
   {
       $trick = new Trick();
       $image = new Image();
       $this->assertInstanceOf(Trick::class, $trick->addImage($image));
   }

   public function testRemoveImageReturnInstance()
   {
       $trick = new Trick();
       $image = new Image();
       $trick->addImage($image);
       $trick->removeImage($image);
       $this->assertNotContains($image, $trick->getImages());
   }

   public function testGetVideosReturnInstance()
   {
       $trick = new Trick();
       $video1 = new Video();
       $video2 = new Video();
       $video1->setTrick($trick);
       $video2->setTrick($trick);
       $this->assertInstanceOf(Collection::class, $trick->getVideos());
   }

   public function testAddVideoReturnInstance()
   {
       $trick = new Trick();
       $video = new Video();
       $this->assertInstanceOf(Trick::class, $trick->addVideo($video));
   }

   public function testRemoveVideoReturnInstance()
   {
       $trick = new Trick();
       $video = new Video();
       $trick->addVideo($video);
       $trick->removeVideo($video);
       $this->assertNotContains($video, $trick->getVideos());
   }

   public function testGetImageForwardReturnInstance()
   {
       $trick = new Trick();
       $this->assertInstanceOf(ImageForward::class, $trick->getImageForward());
   }
}