<?php

namespace DFPA\Agent;

use DFPA\Sensors\SensorInterface;
use DFPA\Analyzer\SignalAnalyzer;

class DFPAAgent
{
    /** @var SensorInterface[] */
    private array $sensors = [];
    private SignalAnalyzer $analyzer;

    public function __construct()
    {
        $this->analyzer = new SignalAnalyzer();
    }

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

        $this->analyzer->ingest($events);

        return $this->analyzer->analyze();
    }
}
