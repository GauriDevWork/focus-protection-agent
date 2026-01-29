<?php

namespace DFPA\Signals;

class Signal
{
    public string $type;
    public float $confidence;
    public array $evidence;
    public int $timestamp;

    public function __construct(
        string $type,
        float $confidence,
        array $evidence = []
    ) {
        $this->type = $type;
        $this->confidence = $confidence;
        $this->evidence = $evidence;
        $this->timestamp = time();
    }
}
