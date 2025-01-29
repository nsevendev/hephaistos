<?php

declare(strict_types=1);

namespace Heph\Infrastructure\ApiResponse\Exception\Event;

use Heph\Infrastructure\ApiResponse\Exception\Type\ApiResponseExceptionInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ApiResponseExceptionListener
{
    #[AsEventListener(event: ExceptionEvent::class, priority: 0)]
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ApiResponseExceptionInterface) {
            $event->setResponse($exception->toApiResponse());
        }

    }
}
