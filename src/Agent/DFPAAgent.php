<?php

namespace DFPA\Agent;

use DFPA\Sensors\SensorInterface;
use DFPA\Analyzer\SignalAnalyzer;
use DFPA\Decisions\DecisionEngine;
use DFPA\Interventions\InterventionDispatcher;
use DFPA\Interventions\CliIntervention;
// use DFPA\Interventions\OsNotificationIntervention;
use DFPA\Utils\Logger;

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

        // Optional OS notifications (opt-in only)
        // $this->dispatcher->register(new OsNotificationIntervention());
    }

    public function registerSensor(SensorInterface $sensor): void
    {
        $this->sensors[] = $sensor;
    }

    /**
     * Run one DFPA observation cycle
     *
     * @return array{signals: array, decision: mixed}
     */
    public function run(): array
    {
        $events = [];

        // --------------------------
        // 1. Collect Events
        // --------------------------
        foreach ($this->sensors as $sensor) {
            $events = array_merge($events, $sensor->collect());
        }

        // 🔹 Log raw events
        foreach ($events as $event) {
            Logger::logLine(
                "[" . date('H:i') . "] EVENT {$event->type} " .
                json_encode($event->metadata)
            );
        }

        // --------------------------
        // 2. Analyze Signals
        // --------------------------
        $this->analyzer->ingest($events);
        $signals = $this->analyzer->analyze();

        // 🔹 Log signals
        foreach ($signals as $signal) {
            Logger::logLine(
                "[" . date('H:i') . "] SIGNAL {$signal->type} " .
                "confidence={$signal->confidence} " .
                json_encode($signal->evidence)
            );
        }

        // --------------------------
        // 3. Decide (or stay silent)
        // --------------------------
        $decision = $this->decisionEngine->decide($signals);

        // 🔹 Log decision (rare)
        if ($decision) {
            Logger::logLine(
                "[" . date('H:i') . "] DECISION {$decision->action} reason=" .
                implode(',', $decision->reasons)
            );
        }

        // --------------------------
        // 4. Dispatch Intervention
        // --------------------------
        if ($decision) {
            $this->dispatcher->dispatch($decision);
        }

        return [
            'signals'  => $signals,
            'decision' => $decision
        ];
    }
}
