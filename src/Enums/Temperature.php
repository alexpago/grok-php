<?php

namespace Pago\Grok\Enums;

/**
 * Request temperature values.
 * Than more creative the response, the higher the temperature.
 */
enum Temperature: string
{
    case CODER = '0.0';
    case MATH_EXPERT = '0.1';
    case ANALYST = '1.0';
    case DATA_CLEANER = '1.1';
    case COMPANION = '1.3';
    case POET = '1.6';

    /**
     * Default temperature.
     * @return self
     */
    public static function default(): self
    {
        return self::COMPANION;
    }
}