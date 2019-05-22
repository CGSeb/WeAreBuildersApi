<?php

namespace App\Command;

use App\Entity\Profile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateProfileCommand extends Command
{
    protected static $defaultName = 'app:create-profile';

    private $entitymanager;

    public function __construct(EntityManagerInterface $entitymanager)
    {
        parent::__construct();
        $this->entitymanager = $entitymanager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Create a profile')
            ->addArgument('firstName', InputArgument::REQUIRED, 'First Name')
            ->addArgument('lastName', InputArgument::REQUIRED, 'Last Name')
            ->addArgument('email', InputArgument::REQUIRED, 'Email')
            ->addArgument('birthDate', InputArgument::REQUIRED, 'Date of Birth')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io             = new SymfonyStyle($input, $output);
        $firstName      = $input->getArgument('firstName');
        $lastName       = $input->getArgument('lastName');
        $email          = $input->getArgument('email');
        $birthDateText  = $input->getArgument('birthDate');

        try {
            $timeBirth  = strtotime($birthDateText);
            $birthDate  = new \DateTime('Y-m-d',$timeBirth);
            $profile    = new Profile();
            $profile->setFirstName($firstName);
            $profile->setLastName($lastName);
            $profile->setEmail($email);
            $profile->setBirthDate($birthDate);

            $this->entitymanager->persist($profile);
            $this->entitymanager->flush();
        } catch (\Exception $e) {
            $io->error('Error while creating the profile!');
        }

        $io->success('Profile ' . $profile->getFullName() . ' created!');
    }
}
