<?php

declare(strict_types=1);

namespace Functional\Controller\Api\TermsConditionsArticle;

use Doctrine\DBAL\Exception;
use Heph\Controller\Api\TermsConditionsArticle\DeleteTermsConditionsArticle;
use Heph\Controller\Api\TermsConditionsArticle\ListTermsConditionsArticle;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleDto;
use Heph\Entity\TermsConditionsArticle\TermsConditionsArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleTitle;
use Heph\Infrastructure\ApiResponse\ApiResponse;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseData;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\Doctrine\Types\Shared\DescriptionType;
use Heph\Infrastructure\Doctrine\Types\Shared\LibelleType;
use Heph\Infrastructure\Doctrine\Types\TermsConditionsArticle\TermsConditionsArticleArticleType;
use Heph\Infrastructure\Doctrine\Types\TermsConditionsArticle\TermsConditionsArticleTitleType;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Message\Command\TermsConditionsArticle\DeleteTermsConditionsArticleCommand;
use Heph\Message\Query\TermsConditionsArticle\GetListTermsConditionsArticleHandler;
use Heph\Repository\TermsConditionsArticle\TermsConditionsArticleRepository;
use Heph\Tests\Faker\Entity\TermsConditionsArticle\TermsConditionsArticleFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

#[
    CoversClass(ListTermsConditionsArticle::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(TermsConditionsArticleDto::class),
    CoversClass(HephSerializer::class),
    CoversClass(GetListTermsConditionsArticleHandler::class),
    CoversClass(TermsConditionsArticleRepository::class),
    CoversClass(TermsConditionsArticle::class),
    CoversClass(TermsConditionsArticleTitle::class),
    CoversClass(TermsConditionsArticleTitleType::class),
    CoversClass(TermsConditionsArticleArticle::class),
    CoversClass(TermsConditionsArticleArticleType::class),
    CoversClass(DeleteTermsConditionsArticle::class),
    CoversClass(DeleteTermsConditionsArticleCommand::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(TermsConditions::class),
    CoversClass(DescriptionType::class),
    CoversClass(LibelleType::class)
]
class DeleteTermsConditionsArticleTest extends HephFunctionalTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @throws Exception
     */
    public function testCreateAndDeleteTermsConditionsArticle(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        $termsConditionsArticle = TermsConditionsArticleFaker::new();

        $entityManager->persist($termsConditionsArticle);
        $entityManager->flush();

        $this->client->request('DELETE', '/api/terms-conditions-article/'.$termsConditionsArticle->id());

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $entityManager->rollBack();
    }
}
