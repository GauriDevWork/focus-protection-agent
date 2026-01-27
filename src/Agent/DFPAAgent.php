<?php

namespace DFPA\Agent;

use DFPA\Sensors\SensorInterface;

class DFPAAgent
{
    /** @var SensorInterface[] */
    private array $sensors = [];

    public function registerSensor(SensorInterface $sensor): void
    {
        $this->sensors[] = $sensor;
    }

    public function run(): array
    {
        $events = [];

        foreach ($this->sensors as $sensor) {
            $events = array_merge($events, $sensor->collect());
        }

        return $events;
    }
}
