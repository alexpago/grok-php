<?php

namespace Pago\Grok\Traits;

use Pago\Grok\Configs\ChatConfig;
use Pago\Grok\Enums\Model;
use Pago\Grok\Enums\Temperature;

/**
 * Trait for clientable classes.
 */
trait Clientable
{
    /**
     * Get the base URL.
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Get the chat config.
     * @return ChatConfig
     */
    public function getConfig(): ChatConfig
    {
        return $this->chatConfig;
    }

    /**
     * Set the request temperature.
     * @param Temperature|float $temperature
     * @return self
     */
    public function setTemperature(Temperature|float $temperature): self
    {
        $this->chatConfig->temperature = $temperature instanceof Temperature ? $temperature->value : $temperature;
        return $this;
    }

    /**
     * Set the request model.
     * @param string $model
     * @return self
     */
    public function setModel(Model|string $model): self
    {
        $this->chatConfig->model = $model instanceof Model ? $model->value : $model;
        return $this;
    }

    /**
     * Alias for setModel.
     * @param Model|string $model
     * @return self
     */
    public function withModel(Model|string $model): self
    {
        $this->setModel($model);
        return $this;
    }
    
    /**
     * Set the request timeout.
     * @param int $timeout
     * @return self
     */
    public function setTimeout(int $timeout): self
    {
        $this->chatConfig->timeout = $timeout;
        return $this;
    }
}