<?php

namespace DFPA\Agent;

use DFPA\Sensors\SensorInterface;
use DFPA\Analyzer\SignalAnalyzer;
use DFPA\Decisions\DecisionEngine;

class DFPAAgent
{
    /** @var SensorInterface[] */
    private array $sensors = [];
    private SignalAnalyzer $analyzer;
    private DecisionEngine $decisionEngine;

    public function __construct()
    {
        $this->analyzer = new SignalAnalyzer();
        $this->decisionEngine = new DecisionEngine();
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

        $signals = $this->analyzer->analyze();

        $decision = $this->decisionEngine->decide($signals);

        return [
            'signals' => $signals,
            'decision' => $decision
        ];
    }
}
