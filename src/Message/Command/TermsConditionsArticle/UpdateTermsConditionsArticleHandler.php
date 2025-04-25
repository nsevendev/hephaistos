<?php

declare(strict_types=1);

namespace Heph\Message\Command\TermsConditionsArticle;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleArticle;
use Heph\Entity\TermsConditionsArticle\ValueObject\TermsConditionsArticleTitle;
use Heph\Repository\TermsConditionsArticle\TermsConditionsArticleRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
class UpdateTermsConditionsArticleHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TermsConditionsArticleRepository $termsConditionsArticleRepository,
    ) {}

    public function __invoke(UpdateTermsConditionsArticleCommand $command): void
    {
        $termsConditionsArticle = $this->termsConditionsArticleRepository->find($command->id);
        if ($termsConditionsArticle) {
            $termsConditionsArticle->setTitle(TermsConditionsArticleTitle::fromValue($command->termsConditionsArticleUpdateDto->title));
            $termsConditionsArticle->setArticle(TermsConditionsArticleArticle::fromValue($command->termsConditionsArticleUpdateDto->article));
            $termsConditionsArticle->setUpdatedAt(new DateTimeImmutable());
            $this->entityManager->persist($termsConditionsArticle);
            $this->entityManager->flush();
        }
    }
}
