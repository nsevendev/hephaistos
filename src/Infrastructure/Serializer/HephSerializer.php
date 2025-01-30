<?php

declare(strict_types=1);

namespace Heph\Infrastructure\Serializer;

use Heph\Infrastructure\Serializer\Normalizer\ValueObjectNormalizer;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\UidNormalizer;
use Symfony\Component\Serializer\Serializer;

class HephSerializer extends Serializer
{
    public function __construct()
    {
        // PropertyInfo extractors for detailed type information
        $propertyInfoExtractor = new PropertyInfoExtractor(
            [],
            [new PhpDocExtractor(), new ReflectionExtractor()]
        );

        $objectNormalizer = new ObjectNormalizer(
            null, // pas besoin de metadata factory
            new CamelCaseToSnakeCaseNameConverter(), // Convertit les noms camelCase <-> snake_case
            null,
            $propertyInfoExtractor
        );

        // Normalizers
        $normalizers = [
            new ValueObjectNormalizer(),
            new UidNormalizer(),                                     // Gère les Uuid
            new DateTimeNormalizer(['datetime_format' => 'Y-m-d']), // Format les dates
            new ArrayDenormalizer(),                                // Pour gérer les tableaux d'objets
            $objectNormalizer,
        ];

        // Encoders
        $encoders = [
            new JsonEncoder(), // Gère les encodages JSON
        ];

        // Appel du parent pour initialiser le Serializer
        parent::__construct($normalizers, $encoders);
    }
}
