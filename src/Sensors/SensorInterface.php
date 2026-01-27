<?php

namespace DFPA\Sensors;

use DFPA\Events\Event;

interface SensorInterface
{
    /**
     * Collect raw events from the environment
     *
     * @return Event[]
     */
    public function collect(): array;
}
