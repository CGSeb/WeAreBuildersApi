<?php

namespace App\Tests;

use App\Entity\Profile;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CreateUserTest extends TestCase
{
    public function testCreateProfile(EntityManagerInterface $entityManager)
    {
        $profile = new Profile();
        $profile->setFirstName('Sebastien');
        $profile->setLastName('Michon');
        $profile->setEmail('sebastien@gmail.com');
        $profile->setBirthDate(new \DateTime());

        $entityManager->persist($profile);
        $entityManager->flush();
        $this->assertIsInt($profile->getId());
    }
}
