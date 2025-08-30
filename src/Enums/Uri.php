<?php

namespace Pago\Grok\Enums;

/**
 * URI enum.
 */
enum Uri: string
{
    /**
     * Chat completions URI.
     */
    case CHAT = '/v1/chat/completions';

    /**
     * Image generation URI.
     */
    case IMAGE_GENERATION = '/v1/images/generations';
}