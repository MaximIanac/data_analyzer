<?php

namespace App\Services\Sources\Enums;

enum MetricKey: string
{
    case FLAT_AVG_PPM_1ROOM = 'flat_avg_pricePerMeter_1room';
    case FLAT_AVG_PPM_2ROOMS = 'flat_avg_pricePerMeter_2rooms';
}
