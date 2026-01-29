<?php

namespace DFPA\Utils;

class Logger
{
    public static function log(array $items): void
    {
        foreach ($items as $item) {
            echo sprintf(
                "[%s] %s %.2f %s\n",
                date('H:i:s', $item->timestamp),
                $item->type,
                $item->confidence,
                json_encode($item->evidence)
            );
        }
    }
}
