<?php

namespace Pago\Grok\Enums;

/**
 * Image detail enum.
 * Use for image understanding.
 */
enum ImageDetail: string
{
    case HIGH = 'high';
    case MEDIUM = 'medium';
    case LOW = 'low';
}