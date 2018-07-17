<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Avatar;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Les noms d'utilisateurs à créer
    $listNames = array('Florian', 'Marine', 'Charlotte');

    foreach ($listNames as $name) {
      $avatar = new Avatar;

      $avatar->setUrl($name.'.png');

      $user = new User;
      // Le nom d'utilisateur et le mot de passe sont identiques pour l'instant
      $user->setUsername($name);
      $user->setPassword($name);
      $user->setEmail($name.'@fixtures.com');
      $user->setToken($name.'123456789');
      $user->setSalt('');
      $user->setIsActive(1);
      $user->setAvatar($avatar);
      // On définit uniquement le role ROLE_USER qui est le role de base
      $user->setRoles(array('ROLE_USER'));

      // On le persiste
      $manager->persist($user);
    };

        $manager->flush();
    }
}
