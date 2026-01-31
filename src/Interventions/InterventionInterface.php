<?php

namespace DFPA\Interventions;

use DFPA\Decisions\Decision;

interface InterventionInterface
{
    public function supports(Decision $decision): bool;
    public function execute(Decision $decision): void;
}
