<?php

namespace App\State;

use ApiPlatform\Doctrine\Orm\State\Processor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserPasswordHasher implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository $userRepository
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): User
    {
        if (!$data instanceof User) {
            return $data;
        }

        // Vérifier l'unicité de l'email
        $existingUserWithEmail = $this->userRepository->findOneBy(['email' => $data->getEmail()]);
        
        // Si c'est une mise à jour (PUT ou PATCH)
        if (($operation->getName() === 'put' || $operation->getName() === 'patch') && $data->getId()) {
            // Vérifier si l'email existe déjà pour un autre utilisateur
            if ($existingUserWithEmail && $existingUserWithEmail->getId() !== $data->getId()) {
                throw new BadRequestHttpException('Cet email est déjà utilisé par un autre utilisateur.');
            }
            
            // Récupérer l'utilisateur existant pour préserver le mot de passe si non modifié
            $existingUser = $this->entityManager->getRepository(User::class)->find($data->getId());
            if ($existingUser) {
                // Si le mot de passe n'est pas fourni, conserver l'ancien
                if (!$data->getPassword()) {
                    $data->setPassword($existingUser->getPassword());
                }
                // Préserver les rôles si non modifiés (ils ne sont pas dans le groupe user:write par défaut)
                if (empty($data->getRoles())) {
                    $data->setRoles($existingUser->getRoles());
                }
            }
        } else {
            // Pour la création (POST), vérifier si l'email existe déjà
            if ($existingUserWithEmail) {
                throw new BadRequestHttpException('Cet email est déjà utilisé.');
            }
        }

        // Hasher le mot de passe si fourni
        $plainPassword = $data->getPassword();
        if ($plainPassword) {
            // Vérifier si c'est déjà un hash (ne pas re-hasher un hash)
            $passwordInfo = password_get_info($plainPassword);
            if (!$passwordInfo['algo']) {
                // C'est un mot de passe en clair, le hasher
                $hashedPassword = $this->passwordHasher->hashPassword(
                    $data,
                    $plainPassword
                );
                $data->setPassword($hashedPassword);
            }
            // Si c'est déjà un hash, on le garde tel quel
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }
}
