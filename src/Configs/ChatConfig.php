<?php

namespace Pago\Grok\Configs;

use Pago\Grok\Enums\Model;
use Pago\Grok\Enums\Temperature;

/**
 * Chat config.
 */
final class ChatConfig
{
    /**
     * @var Model|string
     */
    public Model|string|null $model;

    /**
     * @var Temperature|float
     */
    public Temperature|float|null $temperature;

    /**
     * @var int
     */
    public int $timeout;

    /**
     * @param Model|string|null $model The model to use for the request.
     * @param Temperature|float|null $temperature The temperature of the request.
     * @param int $timeout The timeout of the request.
     * @return void
     */
    public function __construct(
        Model|string|null $model = null,
        Temperature|float|null $temperature = null,
        int $timeout = 30
    ) 
    { 
        $this->model = $model ?? Model::default();
        $this->temperature = $temperature ?? Temperature::default();
        $this->timeout = $timeout;
    }
}