<?php

namespace Pago\Grok\Enums\ImageGeneration;

/**
 * Image output format enum.
 * Warning: Grok is currently returns only a URL as output format, 
 * but according to the documentation it should also be possible to use base64.
 */
enum ImageOutputFormat: string
{
    case URL = 'url';
    case BASE64 = 'base64';
}