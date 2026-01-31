<?php

namespace DFPA\Decisions;

use DFPA\Signals\Signal;

class DecisionEngine
{
    private array $lastDecisionAt = [];
    private int $cooldownSeconds = 300; // 5 minutes

    /**
     * @param Signal[] $signals
     */
    public function decide(array $signals): ?Decision
    {
        // Sort strongest signals first
        usort($signals, fn($a, $b) => $b->confidence <=> $a->confidence);

        foreach ($signals as $signal) {
            if ($signal->confidence < 0.1) {
                continue;
            }

            if ($this->isCoolingDown($signal->type)) {
                continue;
            }

            $decision = $this->mapSignalToDecision($signal);

            if ($decision) {
                $this->lastDecisionAt[$signal->type] = time();
                return $decision;
            }
        }

        return null; // silence
    }

    private function isCoolingDown(string $signalType): bool
    {
        if (!isset($this->lastDecisionAt[$signalType])) {
            return false;
        }

        return (time() - $this->lastDecisionAt[$signalType]) < $this->cooldownSeconds;
    }

    private function mapSignalToDecision(Signal $signal): ?Decision
    {
        return match ($signal->type) {
            'EXTERNAL_INTERRUPTION' =>
                new Decision('SUGGEST_FOCUS_LOCK', [$signal->type]),

            'HIGH_ACTIVITY_DENSITY' =>
                new Decision('SUGGEST_PAUSE', [$signal->type]),

            default => null
        };
    }
}
