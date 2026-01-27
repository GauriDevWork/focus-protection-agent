<?php

namespace DFPA\Events;

class Event
{
    public string $type;
    public int $timestamp;
    public array $metadata;

    public function __construct(string $type, array $metadata = [])
    {
        $this->type = $type;
        $this->timestamp = time();
        $this->metadata = $metadata;
    }
}
