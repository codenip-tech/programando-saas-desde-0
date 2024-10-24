<?php

namespace App\Value;

enum ResponseFormatterContentType: string
{
    case APPLICATION_JSON = 'application/json';
    case APPLICATION_XML = 'application/xml';
}
