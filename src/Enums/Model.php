<?php

namespace Pago\Grok\Enums;

/**
 * Grok models.
 * @method static Models GROK_3()
 * @method static Models GROK_3_MINIT()
 * @method static Models GROK_4()
 * @method static Models GROK_4_0709()
 */
enum Model: string
{
    case GROK_3 = 'grok-3';
    case GROK_3_MINI = 'grok-3-mini';
    case GROK_4 = 'grok-4';
    case GROK_4_0709 = 'grok-4-0709';

    /**
     * Default model.
     * @return self
     */
    public static function default(): self
    {
        return self::GROK_4;
    }
}