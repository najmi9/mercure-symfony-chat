<?php

declare(strict_types=1);

namespace App\Infrastructure\Queue\Handler;

use App\Infrastructure\Notification\EmailNotifier;
use App\Infrastructure\Queue\Message\ServiceMethodMessage;
use Psr\Container\ContainerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

/**
 * The handler of the messages.
 */
class ServiceMethodMessageHandler implements MessageHandlerInterface, ServiceSubscriberInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(ServiceMethodMessage $message): void
    {
        /** @var callable $callable */
        $callable = [
            $this->container->get($message->getServiceName()),
            $message->getMethod(),
        ];

        \call_user_func_array($callable, $message->getParams());
    }

    /**
     * Get private services, because we can't get private services from the container.
     *
     * @return array private services
     */
    public static function getSubscribedServices(): array
    {
        return [
            EmailNotifier::class => EmailNotifier::class,
            HubInterface::class => HubInterface::class,
        ];
    }
}
