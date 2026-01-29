<?php

namespace DFPA\Decisions;

class Decision
{
    public string $action;
    public array $reasons;
    public int $timestamp;

    public function __construct(string $action, array $reasons = [])
    {
        $this->action = $action;
        $this->reasons = $reasons;
        $this->timestamp = time();
    }
}
