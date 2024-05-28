<?php

namespace App\Enums;

enum EItemStatus: int {

    case AVAILABLE = 1;

    case ABSENT = 2;

    case BLOCKED = 3;
}
