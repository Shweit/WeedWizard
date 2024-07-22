<?php

namespace App\Service;

enum InteractionsType: string
{
    case VIEW = 'view';
    case LIKE = 'like';
    case COMMENT = 'comment';
    case SHARE = 'share';
}
