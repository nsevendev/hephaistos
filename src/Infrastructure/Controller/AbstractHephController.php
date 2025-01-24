<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Controller;

use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\Serializer\HephSerializer;
use LogicException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

abstract class AbstractHephController
{
    public function __construct(protected HephSerializer $serializer, protected ValidatorInterface $validator) {}

    /**
     * Valide une liste de contraintes et lève une exception personnalisée en cas d'erreurs.
     */
    private function validateArgumentList(ConstraintViolationListInterface $errors, callable $fnException): void
    {
        $errorList = [];

        if ($errors->count() > 0) {
            foreach ($errors as $error) {
                $errorList[] = Error::create(
                    key: $error->getPropertyPath(),
                    message: $error->getMessage()
                );
            }

            if (is_callable($fnException)) {
                throw $fnException($errorList);
            }

            throw new LogicException('Le handler exception n\'est pas valide.');
        }
    }

    /**
     * Désérialise et valide un DTO.
     *
     * @throws Throwable
     */
    protected function deserializeAndValidate(
        string $data,
        string $dtoClass,
        callable $fnException,
    ): object {
        // Désérialisation
        $dto = $this->serializer->deserialize($data, $dtoClass, 'json');

        // Validation
        $this->validate(
            errors: $this->validator->validate($dto),
            fnException: $fnException
        );

        return $dto;
    }

    /**
     * @throws Throwable
     */
    protected function validate(ConstraintViolationListInterface $errors, callable $fnException): void
    {
        $this->validateArgumentList($errors, $fnException);
    }
}
