<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        
        $tag1 = new Tag();
        $tag1->setName('Symfony');
        $manager->persist($tag1);

        $tag2 = new Tag();
        $tag2->setName('PHP');
        $manager->persist($tag2);

        
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setFirstName('Admin');
        $user->setLastName('Mock');
        $user->setCountryCode('FR');

        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            'password123'
        ));
        $manager->persist($user);

        $user = new User();
        $user->setFirstName('Admin');
        $user->setLastName('Mock');
        $user->setEmail('editor@example.com');
        $user->setCountryCode('FR');

        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            'password123'
        ));

        $user->setRoles(['ROLE_EDITOR']); 
        $manager->persist($user);

        $user = new User();
        $user->setFirstName('Admin');
        $user->setLastName('Mock');
        $user->setCountryCode('FR');

        $user->setEmail('admin@example.com');
        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            'password123'
        ));
        $user->setRoles(['ROLE_ADMIN']); 
        $manager->persist($user);

        
        $article = new Article();
        $article->setTitle('Welcome to Symfony News');
        $article->setBody('This is the body of the first article.');
        $article->setPublicationDate(new \DateTime());
        $article->setAuthor($user);
        $article->addTag($tag1);
        $article->addTag($tag2);
        $manager->persist($article);

        $manager->flush();
    }
}
