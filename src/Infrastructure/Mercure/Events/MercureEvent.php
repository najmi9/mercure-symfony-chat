<?php

declare(strict_types=1);

namespace App\Infrastructure\Mercure\Events;

class MercureEvent
{
    private array $channels;
    private array $data;

    public function __construct(array $channels, array $data)
    {
        $this->channels = $channels;
        $this->data = $data;
    }

    /**
     * Get the value of data.
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Get the value of channels.
     */
    public function getChannels(): array
    {
        return $this->channels;
    }
}
