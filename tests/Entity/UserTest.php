<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Avatar;
use App\Service\TokenGen;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /*
    public function testUser()
    {
        $user = new User();
        $email = 'test@test.com';
        $user->setEmail($email);
        $this->assertInternalType('string', $email);
        $this->assertSame($email, $user->getEmail());
    }

    public function testUsername()
    {
        $user = new User();
        $username = 'Test';
        $user->setUsername($username);
        $this->assertInternalType('string', $username);
        $this->assertSame($username, $user->getUsername());
    }

    public function testPassword()
    {
        $user = new User();
        $password = '$2y$13$lMP9HlFDFdlOT0tDuubCt.vYMFpjldR.IiHH4Vtde1bngDzvEJlrO';
        $user->setPassword($password);
        $this->assertInternalType('string', $password);
        $this->assertSame($password, $user->getPassword());
    }

    public function testToken()
    {
        $user = new User();
        $tokenGen = new TokenGen();
        $token = $tokenGen->newToken();
        $user->setToken($token);
        $this->assertInternalType('string', $token);
        $this->assertSame($token, $user->getToken());
    }

    public function testIsActive()
    {
        $user = new User();
        $isActive = true;
        $user->setIsActive($isActive);
        $this->assertInternalType('boolean', $isActive);
        $this->assertSame($isActive, $user->getIsActive());
    }

    public function testAvatar()
    {
        $user = new User();
        $avatar = new Avatar();
        $user->setAvatar($avatar);
        $this->assertInstanceOf(Avatar::class, $user->getAvatar());
    }

    public function testSalt()
    {
        $user = new User();
        $salt = '';
        $user->setSalt($salt);
        $this->assertInternalType('string', $user->getSalt());
        $this->assertSame($salt, $user->getSalt());
    }

    public function testRoles()
    {
        $user = new User();
        $role = array('ROLE_TEST');
        $user->setRoles($role);
        $this->assertInternalType('array', $user->getRoles());
        $this->assertSame($role, $user->getRoles());
    }

    public function testRawPassword()
    {
        $user = new User();
        $rawPassword = 'TestRawPassword';
        $user->setRawPassword($rawPassword);
        $this->assertInternalType('string', $user->getRawPassword());
        $this->assertSame($rawPassword, $user->getRawPassword());
    }
    */

    public function testGetEmailReturnType()
    {
        $user = new User();
        $email = 'test@test.com';
        $user->setEmail($email);
        $this->assertInternalType('string', $user->getEmail());
    }

    public function testSetEmailReturnInstance()
    {
        $user = new User();
        $email = 'test@test.com';
        $this->assertInstanceOf(User::class, $user->setEmail($email));
    }

    public function testGetUsernameReturnType()
    {
        $user = new User();
        $username = 'Test Username';
        $user->setUsername($username);
        $this->assertInternalType('string', $user->getUsername());
    }

    public function testSetUsernameReturnInstance()
    {
        $user = new User();
        $username = 'Test Username';
        $this->assertInstanceOf(User::class, $user->setUsername($username));
    }

    public function testGetPasswordReturnType()
    {
        $user = new User();
        $password = '$2y$13$lMP9HlFDFdlOT0tDuubCt.vYMFpjldR.IiHH4Vtde1bngDzvEJlrO';
        $user->setPassword($password);
        $this->assertInternalType('string', $user->getPassword());
    }

    public function testSetPasswordReturnInstance()
    {
        $user = new User();
        $password = '$2y$13$lMP9HlFDFdlOT0tDuubCt.vYMFpjldR.IiHH4Vtde1bngDzvEJlrO';
        $this->assertInstanceOf(User::class, $user->setPassword($password));
    }

    public function testGetTokenReturnType()
    {
        $user = new User();
        $tokenGen = new TokenGen();
        $token = $tokenGen->newToken();
        $user->setToken($token);
        $this->assertInternalType('string', $user->getToken());
    }

    public function testSetTokenReturnInstance()
    {
        $user = new User();
        $tokenGen = new TokenGen();
        $token = $tokenGen->newToken();
        $this->assertInstanceOf(User::class, $user->setToken($token));
    }

    public function testGetIsActiveReturnType()
    {
        $user = new User();
        $this->assertInternalType('boolean', $user->getIsActive());
    }

    public function testSetIsActiveReturnInstance()
    {
        $user = new User();
        $isActive = true;
        $this->assertInstanceOf(User::class, $user->setIsActive($isActive));
    }

    public function testGetAvatarReturnInstance()
    {
        $user = new User();
        $this->assertInstanceOf(Avatar::class, $user->getAvatar());
    }

    public function testSetAvatarReturnInstance()
    {
        $user = new User();
        $avatar = new Avatar();
        $this->assertInstanceOf(User::class, $user->setAvatar($avatar));
    }

    public function testGetSaltReturnType()
    {
        $user = new User();
        $salt = '';
        $user->setSalt($salt);
        $this->assertInternalType('string', $user->getSalt());
    }

    public function testSetSaltReturnInstance()
    {
        $user = new User();
        $salt = '';
        $this->assertInstanceOf(User::class, $user->setSalt($salt));
    }

    public function testGetRolesReturnType()
    {
        $user = new User();
        $this->assertInternalType('array', $user->getRoles());
    }

    public function testSetRolesReturnInstance()
    {
        $user = new User();
        $role = array('ROLE_TEST');
        $this->assertInstanceOf(User::class, $user->setRoles($role));
    }

    public function testGetRawPasswordReturnType()
    {
        $user = new User();
        $rawPassword = 'TestRawPassword';
        $user->setRawPassword($rawPassword);
        $this->assertInternalType('string', $user->getRawPassword());
    }

    public function testSetRawPasswordReturnInstance()
    {
        $user = new User();
        $rawPassword = 'TestRawPassword';
        $this->assertInstanceOf(User::class, $user->setRawPassword($rawPassword));
    }
}