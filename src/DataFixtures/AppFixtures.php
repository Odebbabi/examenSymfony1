<?php

namespace App\DataFixtures;

use \App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFullName('admin');

        $password = $this->encoder->encodePassword($user, '123456');
        $user->setPassword($password);

        $user->setEmail('admin@admin.com');
        $date = DateTime::createFromFormat('j-M-Y', '17-Feb-1999');
        $user->setDateBirth($date);
        $user->setAccountType('ADMIN');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setIsVerified(true);


        $manager->persist($user);
        $manager->flush();
    }
}
