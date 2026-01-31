<?php

namespace DFPA\Interventions;

use DFPA\Decisions\Decision;

class CliIntervention implements InterventionInterface
{
    public function supports(Decision $decision): bool
    {
        return true; // CLI supports all decisions
    }

    public function execute(Decision $decision): void
    {
        echo PHP_EOL;
        echo "[DFPA] {$decision->action}" . PHP_EOL;
        echo "Reason: " . implode(', ', $decision->reasons) . PHP_EOL;
    }
}
