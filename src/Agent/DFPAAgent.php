<?php

namespace DFPA\Agent;

use DFPA\Sensors\SensorInterface;
use DFPA\Analyzer\SignalAnalyzer;
use DFPA\Decisions\DecisionEngine;
use DFPA\Interventions\InterventionDispatcher;
use DFPA\Interventions\CliIntervention;
use DFPA\Interventions\OsNotificationIntervention;

class DFPAAgent
{
    /** @var SensorInterface[] */
    private array $sensors = [];
    private SignalAnalyzer $analyzer;
    private DecisionEngine $decisionEngine;
    private InterventionDispatcher $dispatcher;

    public function __construct()
    {
        $this->analyzer = new SignalAnalyzer();
        $this->decisionEngine = new DecisionEngine();
        $this->dispatcher = new InterventionDispatcher();
        $this->dispatcher->register(new CliIntervention());
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
        echo "DEBUG: SIGNAL COUNT = " . count($signals) . PHP_EOL;

        foreach ($signals as $signal) {
            echo "DEBUG SIGNAL → {$signal->type} ({$signal->confidence})" . PHP_EOL;
        }
        $decision = $this->decisionEngine->decide($signals);
        if ($decision) {
            $this->dispatcher->dispatch($decision);
        }
        return [
            'signals' => $signals,
            'decision' => $decision
        ];
    }
}
