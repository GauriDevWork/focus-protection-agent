<?php

namespace DFPA\Utils;

class Logger
{
    public static function log(array $events): void
    {
        foreach ($events as $event) {
            echo sprintf(
                "[%s] %s %s\n",
                date('H:i:s', $event->timestamp),
                $event->type,
                json_encode($event->metadata)
            );
        }
    }
}
