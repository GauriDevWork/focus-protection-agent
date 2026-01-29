<?php

namespace DFPA\Analyzer;

use DFPA\Events\Event;
use DFPA\Signals\Signal;

class SignalAnalyzer
{
    /** @var Event[] */
    private array $eventBuffer = [];

    private int $windowSeconds = 300; // 5 minutes

    public function ingest(array $events): void
    {
        foreach ($events as $event) {
            $this->eventBuffer[] = $event;
        }

        $this->pruneOldEvents();
    }

    /**
     * @return Signal[]
     */
    public function analyze(): array
    {
        $signals = [];

        $signals = array_merge(
            $signals,
            $this->detectGitActivity(),
            $this->detectExternalInterruptions(),
            $this->detectRapidEvents()
        );

        return $signals;
    }

    private function pruneOldEvents(): void
    {
        $now = time();

        $this->eventBuffer = array_filter(
            $this->eventBuffer,
            fn($e) => ($now - $e->timestamp) <= $this->windowSeconds
        );
    }

        private function detectGitActivity(): array
    {
        $events = array_filter(
            $this->eventBuffer,
            fn($e) => $e->type === 'GIT_ACTIVITY'
        );

        if (count($events) === 0) {
            return [];
        }

        $changedFiles = array_sum(
            array_map(fn($e) => $e->metadata['changed_files'] ?? 0, $events)
        );

        $confidence = min(1.0, $changedFiles / 10);

        return [
            new Signal(
                'CODE_ACTIVITY',
                $confidence,
                ['changed_files' => $changedFiles]
            )
        ];
    }

        private function detectExternalInterruptions(): array
    {
        $events = array_filter(
            $this->eventBuffer,
            fn($e) => $e->type === 'EXTERNAL_NOTIFICATION'
        );

        $count = count($events);

        if ($count < 2) {
            return [];
        }

        $confidence = min(1.0, $count / 5);

        return [
            new Signal(
                'EXTERNAL_INTERRUPTION',
                $confidence,
                ['notifications' => $count]
            )
        ];
    }

        private function detectRapidEvents(): array
    {
        if (count($this->eventBuffer) < 5) {
            return [];
        }

        return [
            new Signal(
                'HIGH_ACTIVITY_DENSITY',
                0.6,
                ['events' => count($this->eventBuffer)]
            )
        ];
    }
}
