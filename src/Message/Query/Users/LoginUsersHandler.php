<?php

declare(strict_types=1);

namespace Heph\Message\Query\Users;

use Heph\Repository\Users\UsersRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Heph\Entity\Users\Users;

#[AsMessageHandler]
class LoginUsersHandler
{
    public function __construct(
        private UsersRepository $usersRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface $jwtManager,
    ) {}

    public function __invoke(LoginUsersQuery $query): ?string
    {
        $loginDto = $query->usersLoginDto;

        /** @var Users|null $user */
        $user = $this->usersRepository->findOneBy(['username' => $loginDto->username]);

        if ($user) {
            if ($this->passwordHasher->isPasswordValid($user, $loginDto->password)) {
                return $this->jwtManager->create($user);
            }
        }

        return null;
    }
}
