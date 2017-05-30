<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AddUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:create-user')
            ->setDescription('Creates a new user.')
            ->setHelp('This command helps you to create a new user by providing its username, and its token.')
            ->addArgument('username', InputArgument::OPTIONAL, 'The username of the user')
            ->addArgument('token', InputArgument::OPTIONAL, 'The token of the user');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('User Creation');

        $username = $input->getArgument('username');

        if (!$username) {
            $username = $io->ask('Please choose a username for the new user');
        }

        $token = $input->getArgument('token');

        if (!$token) {
            $token = $io->ask('Please choose a token for the new user');
        }

        $user = new User($username, $token);
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        $io->success(sprintf('User "%s" created successfully!', $username));
    }
}
