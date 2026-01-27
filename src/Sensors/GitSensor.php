<?php

namespace DFPA\Sensors;

use DFPA\Events\Event;

class GitSensor implements SensorInterface
{
    public function collect(): array
    {
        $command = stripos(PHP_OS, 'WIN') === 0
            ? 'git status --porcelain'
            : 'git status --porcelain 2>/dev/null';

        $output = shell_exec($command);

        if (!$output) {
            return [];
        }

        $lines = substr_count(trim($output), PHP_EOL) + 1;

        return [
            new Event('GIT_ACTIVITY', [
                'changed_files' => $lines
            ])
        ];
    }
}
