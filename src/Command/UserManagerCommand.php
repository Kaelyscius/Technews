<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserManagerCommand extends Command
{
    private $io;
    private $em;

    public function __construct(?string $name = null, EntityManagerInterface $manager)
    {
        parent::__construct($name);
        $this->em = $manager;
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:user-manager')
            // the short description shown while running "php bin/console list"
            ->setDescription('Gestions de nos utilisateur.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Cette commande permet de voir la liste des utilisateurs et leurs attribuer des rôles ');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);

        $this->io->title("Bienvenue dans le gestionnaire des utilisateurs");
//        $this->io->section("Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatiqu.");
//        $this->io->note("Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise ");
//        $this->io->caution("Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise ");
//        $this->io->success("Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise ");
//        $this->io->error("Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise ");
//        $this->io->warning("Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise ");
//        $this->io->table(
//            ['Header 1', 'Header2'],
//            [
//                ['Cellule 1' => "2",'Cellule 2' => "2",]
//                ]
//        );
//        $this->io->choice("Quelle est la couleur du cheval blanc?", ['bleu', 'vert', 'blanc'], 'bleu');

        $this->displayMenu();
    }

    /**
     *
     */
    private function displayMenu()
    {
        while (true) {
            $input = $this->io->choice(
                'Sélectionnez une action',
                [
                    'Afficher la liste des utilisateurs',
                    'Ajouter un role (Nécessite un ID Utilisateur)',
                    'Quitter'
                ]
            );

            switch ($input) {
                case 'Afficher la liste des utilisateurs':
                    $this->displayUserList();
                    break;
                case 'Ajouter un role (Nécessite un ID Utilisateur)':
                    $this->addUserRole();
                    break;
                case 'Quitter':
                    exit();
                    break;
            }
        }
    }

    private function displayUserList()
    {
        $display = [];

        # Récupération des users dans la BDD
        $users = $this->em->getRepository(User::class)->findAll();

        foreach ($users as $user) {
            $display[] = [
                $user->getId(),
                $user->getFirstname() . ' ' . $user->getLastname(),
                $user->getEmail(),
                implode(' | ', $user->getRoles())
            ];
        }
        $this->io->table(['ID', 'FULLNAME', 'EMAIL', 'ROLES'], $display);
    }

    /**
     *
     */
    private function addUserRole()
    {
        $id = $this->io->ask('Saisissez un ID Utilisateur');

        #Récupération du user

        $user = $this->em->getRepository(User::class)->find($id);
        #On vérifie qu'on a bien un utilisateur
        if (!$user) {
            $this->io->error('PAS D\'UTILISATEUR ! ');
            return;
        }

        $roles = [
            'ROLE_USER',
            'ROLE_AUTHOR',
            'ROLE_EDITOR',
            'ROLE_CORRECTOR',
            'ROLE_PUBLISHER',
            'ROLE_ADMIN',
        ];
        $role = $this->io->choice(
            'Quel rôle souhaitez vous ajouter',
            array_diff($roles, $user->getRoles())
        );

        #On verifie que l'utilisateur n'a pas déjà ce role.
//        if (!$user->addRole($role)){
//            $this->io->caution('Cet utilisateur a déjà ce rôle');
//        } else{

        $user->addRole($role);
        #On sauvegarde en base
        $this->em->flush();

        #Message de confirmation
        $this->io->success("Le role $role a bien été attribué à " . $user->getEmail());
//        }
    }
}
