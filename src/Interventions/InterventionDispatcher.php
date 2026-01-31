<?php

namespace DFPA\Interventions;

use DFPA\Decisions\Decision;

class InterventionDispatcher
{
    /** @var InterventionInterface[] */
    private array $interventions = [];

    public function register(InterventionInterface $intervention): void
    {
        $this->interventions[] = $intervention;
    }

    public function dispatch(Decision $decision): void
    {
        foreach ($this->interventions as $intervention) {
            if ($intervention->supports($decision)) {
                $intervention->execute($decision);
            }
        }
    }
}
