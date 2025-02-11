<?php

declare(strict_types=1);

namespace Heph\Tests\Faker\Dto\TermsConditions;

use Heph\Entity\TermsConditions\Dto\TermsConditionsCreateDto;
use Heph\Tests\Faker\Dto\InfoDescriptionModel\InfoDescriptionModelCreateDtoFaker;
use Symfony\Component\Uid\Uuid;

class TermsConditionsCreateDtoFaker
{
    public static function new(): TermsConditionsCreateDto
    {
        return new TermsConditionsCreateDto(
            id: Uuid::v7(),
            infoDescriptionModel: InfoDescriptionModelCreateDtoFaker::new(),
            createdAt: new \DateTimeImmutable('2000-03-31 10:00:00'),
            updatedAt: new \DateTimeImmutable('2000-03-31 12:00:00')
        );
    }
}
