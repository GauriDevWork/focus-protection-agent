<?php

namespace DFPA\Sensors;

use DFPA\Events\Event;

class ActivitySensor implements SensorInterface
{
    public function collect(): array
    {
        return [
            new Event('AGENT_HEARTBEAT', [
                'status' => 'alive'
            ])
        ];
    }
}
