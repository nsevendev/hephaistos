<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Serializer\Normalizer;

use Heph\Infrastructure\Shared\Type\ValueObjectInterface;
use LogicException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ValueObjectNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function normalize(mixed $object, ?string $format = null, array $context = []): string|int|float|bool
    {
        if (false === $object instanceof ValueObjectInterface) {
            throw new LogicException('Expected object to implement ValueObjectInterface.');
        }

        // Sérialisation : Retourne la valeur interne du VO
        return $object->value();
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        // Vérifie si l'objet implémente l'interface ValueObjectInterface
        return $data instanceof ValueObjectInterface;

    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            ValueObjectInterface::class => true, // Indique que tous les types implémentant cette interface sont pris en charge
        ];
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        // Désérialisation : Appelle la méthode `fromValue` pour créer l'objet
        if (method_exists($type, 'fromValue')) {
            return $type::fromValue($data);
        }

        throw new LogicException("Cannot denormalize object of type $type without fromValue method.");
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        // Vérifie si le type implémente l'interface ValueObjectInterface
        return is_subclass_of($type, ValueObjectInterface::class);
    }
}
