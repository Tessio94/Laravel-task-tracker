<?php

namespace App\Enums;

enum TaskFrequency: string
{
    case Daily = 'daily';
    case Weekdays = 'weekdays';
    case Weekly = 'weekly';
    case Monthly = 'monthly';
}
