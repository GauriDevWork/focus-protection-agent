<?php

namespace DFPA\Sensors;

use DFPA\Events\Event;

class BasecampSensor implements SensorInterface
{
    public function collect(): array
    {
        // V1 placeholder: simulate notification detection
        return [
            new Event('EXTERNAL_NOTIFICATION', [
                'source' => 'Basecamp'
            ])
        ];
    }
}
