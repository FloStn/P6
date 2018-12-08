<?php

namespace App\Tests\Entity;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Trick;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    public function testGetContentReturnType()
    {
        $comment = new Comment();
        $content = 'Test du contenu d\'un commentaire';
        $comment->setContent($content);
        $this->assertInternalType('string', $comment->getContent());
    }

    public function testSetContentReturnInstance()
    {
        $comment = new Comment();
        $content = 'Test du contenu d\'un commentaire';
        $this->assertInstanceOf(Comment::class, $comment->setContent($content));
    }

    public function testGetPublishDateReturnInstance()
    {
        $comment = new Comment();
        $this->assertInstanceOf(\Datetime::class, $comment->getPublishDate());
    }

    public function testSetPublishDateReturnInstance()
    {
        $comment = new Comment();
        $datetime = new \Datetime();
        $this->assertInstanceOf(Comment::class, $comment->setPublishDate($datetime));
    }

    public function testGetAuthorReturnInstance()
    {
        $comment = new Comment();
        $author = new User();
        $comment->setAuthor($author);
        $this->assertInstanceOf(User::class, $comment->getAuthor());
    }

    public function testSetAuthorReturnInstance()
    {
        $comment = new Comment();
        $author = new User();
        $this->assertInstanceOf(Comment::class, $comment->setAuthor($author));
    }

    public function testGetTrickReturnInstance()
    {
        $comment = new Comment();
        $trick = new Trick();
        $comment->setTrick($trick);
        $this->assertInstanceOf(Trick::class, $comment->getTrick());
    }

    public function testSetTrickReturnInstance()
    {
        $comment = new Comment();
        $trick = new Trick();
        $this->assertInstanceOf(Comment::class, $comment->setTrick($trick));
    }
}