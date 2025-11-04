<?php

namespace App\Enums;

enum NewsClient: string
{
    case NEWS_API = 'news-api';
    case NEW_YORK_TIMES = 'new-york-times';
    case GUARDIAN = 'guardian';
}
