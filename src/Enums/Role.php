<?php

namespace Pago\Grok\Enums;

/**
 * Grok chat roles.
 * @method static Role USER()
 * @method static Role SYSTEM()
 */
enum Role: string
{
    case USER = 'user';
    case SYSTEM = 'system';
}