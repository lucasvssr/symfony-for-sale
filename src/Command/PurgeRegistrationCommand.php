<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\SchemaException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

#[AsCommand(
    name: 'app:purge-registration',
    description: 'Add a short description for your command',
)]
class PurgeRegistrationCommand extends Command
{
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:purge-registration')
            ->setDescription('Purge unverified users based on the provided options')
            ->addOption('days', null, InputOption::VALUE_REQUIRED, 'Number of days since last verification')
            ->addOption('delete', null, InputOption::VALUE_NONE, 'Delete unverified users')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force deletion without confirmation');
    }

    /**
     * @throws SchemaException
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Get options
        // parseInt $days = $input->getOption('days');
        $days = $input->getOption('days');
        $delete = $input->getOption('delete');
        $force = $input->getOption('force');

        // Verify that $days is a positive integer
        if (null !== $days && (!is_numeric($days) || $days < 0 || floor($days) != $days)) {
            $output->writeln('<error>Le nombre de jours doit être un entier positif.</error>');

            return Command::FAILURE;
        }
        if (null !== $days && $days < 0) {
            $days = intval($days);
        }

        // Get unverified users since $days
        $since = (null !== $days) ? new \DateTimeImmutable("-{$days} days") : null;
        $unverifiedUsers = $this->userRepository->findUnverifiedUsersSince($since);

        $table = new Table($output);
        $table->setHeaders(['Nom', 'Prénom', 'Email', 'Non vérifié depuis'])
            ->setRows(array_map(function ($user) {
                $unverifiedSince = $user->getRegisteredAt();
                $daysSinceUnverified = $unverifiedSince->diff(new \DateTime())->days;

                return [$user->getLastName(), $user->getFirstName(), $user->getEmail(), $daysSinceUnverified];
            }, $unverifiedUsers));
        $table->render();

        // Delete unverified users if --delete is present
        if ($delete) {
            // Ask for confirmation if --force is not present
            if (!$force) {
                $question = new ConfirmationQuestion('Voulez-vous vraiment supprimer les utilisateurs non vérifiés ? (y/n) ', false);
                $helper = $this->getHelper('question');
                if (!$helper->ask($input, $output, $question)) {
                    return Command::SUCCESS;
                }
            }

            // Delete unverified users
            $this->userRepository->deleteUnverifiedUsersSince($since);

            // Show success message
            $output->writeln(sprintf('<info>%d utilisateur(s) ont été supprimé(s).</info>', count($unverifiedUsers)));
        }

        return Command::SUCCESS;
    }
}
