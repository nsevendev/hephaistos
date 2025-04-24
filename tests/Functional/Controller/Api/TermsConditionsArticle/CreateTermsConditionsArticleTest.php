<?php

declare(strict_types=1);

namespace Heph\Tests\Functional\Controller\Api\TermsConditionsArticle;

use Heph\Controller\Api\TermsConditionsArticle\CreateTermsConditionsArticle;
use Heph\Controller\Api\WorkShop\CreateWorkShop;
use Heph\Entity\InfoDescriptionModel\Dto\InfoDescriptionModelCreateDto;
use Heph\Entity\InfoDescriptionModel\InfoDescriptionModel;
use Heph\Entity\Shared\ValueObject\DescriptionValueObject;
use Heph\Entity\Shared\ValueObject\LibelleValueObject;
use Heph\Entity\TermsConditions\TermsConditions;
use Heph\Entity\TermsConditionsArticle\Dto\TermsConditionsArticleCreateDto;
use Heph\Entity\WorkShop\Dto\WorkShopCreateDto;
use Heph\Infrastructure\ApiResponse\ApiResponse;
use Heph\Infrastructure\ApiResponse\ApiResponseFactory;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseData;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseLink;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMessage;
use Heph\Infrastructure\ApiResponse\Component\ApiResponseMeta;
use Heph\Infrastructure\ApiResponse\Exception\Custom\AbstractApiResponseException;
use Heph\Infrastructure\ApiResponse\Exception\Custom\TermsConditionsArticle\TermsConditionsArticleInvalidArgumentException;
use Heph\Infrastructure\ApiResponse\Exception\Error\Error;
use Heph\Infrastructure\ApiResponse\Exception\Error\ListError;
use Heph\Infrastructure\ApiResponse\Exception\Event\ApiResponseExceptionListener;
use Heph\Infrastructure\Doctrine\Types\Shared\DescriptionType;
use Heph\Infrastructure\Doctrine\Types\Shared\LibelleType;
use Heph\Infrastructure\Serializer\HephSerializer;
use Heph\Infrastructure\Serializer\Normalizer\ValueObjectNormalizer;
use Heph\Message\Command\TermsConditionsArticle\CreateTermsConditionsArticleCommand;
use Heph\Message\Command\WorkShop\CreateWorkShopCommand;
use Heph\Repository\TermsConditions\TermsConditionsRepository;
use Heph\Tests\Faker\Entity\TermsConditions\TermsConditionsFaker;
use Heph\Tests\Functional\HephFunctionalTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

#[
    CoversClass(CreateTermsConditionsArticleCommand::class),
    CoversClass(HephSerializer::class),
    CoversClass(CreateTermsConditionsArticle::class),
    CoversClass(ApiResponse::class),
    CoversClass(ApiResponseFactory::class),
    CoversClass(ApiResponseData::class),
    CoversClass(ApiResponseLink::class),
    CoversClass(ApiResponseMessage::class),
    CoversClass(ApiResponseMeta::class),
    CoversClass(ListError::class),
    CoversClass(ValueObjectNormalizer::class),
    CoversClass(TermsConditionsArticleCreateDto::class),
    CoversClass(AbstractApiResponseException::class),
    CoversClass(TermsConditionsArticleInvalidArgumentException::class),
    CoversClass(Error::class),
    CoversClass(ApiResponseExceptionListener::class),
    CoversClass(InfoDescriptionModelCreateDto::class),
    CoversClass(DescriptionValueObject::class),
    CoversClass(LibelleValueObject::class),
    CoversClass(CreateWorkShop::class),
    CoversClass(WorkShopCreateDto::class),
    CoversClass(CreateWorkShopCommand::class),
    CoversClass(InfoDescriptionModel::class),
    CoversClass(TermsConditions::class),
    CoversClass(DescriptionType::class),
    CoversClass(LibelleType::class),
]
class CreateTermsConditionsArticleTest extends HephFunctionalTestCase
{
    use InteractsWithMessenger;

    private KernelBrowser $client;
    private TermsConditionsRepository $termsConditionsRepository;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testInvokeReturnResponseSucces(): void
    {
        $termsConditions = TermsConditionsFaker::new();
        $this->persistAndFlush($termsConditions);

        $payload = json_encode([
            'terms_conditions_id' => $termsConditions->id(),
            'title' => 'title test',
            'article' => 'article test',
        ]);

        $this->client->request('POST', '/api/terms-conditions-article', [], [], [], $payload);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseContent = $this->client->getResponse()->getContent();
        $responseData = json_decode($responseContent, true);

        $this->assertArrayHasKey('message', $responseData['data']);
        $this->assertSame('La demande a été prise en compte.', $responseData['data']['message']);

        /* @var InMemoryTransport $transport */
        $this->transport('async')->queue()->assertNotEmpty();
    }
}
