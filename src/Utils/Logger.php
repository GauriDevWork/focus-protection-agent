<?php

namespace DFPA\Utils;

class Logger
{
    private static function logFile(): string
    {
        $dir = __DIR__ . '/../../logs';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        return $dir . '/dfpa-' . date('Y-m-d') . '.log';
    }

    public static function logLine(string $line): void
    {
        file_put_contents(
            self::logFile(),
            $line . PHP_EOL,
            FILE_APPEND
        );
    }
}
