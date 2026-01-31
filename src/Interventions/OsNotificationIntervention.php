<?php

namespace DFPA\Interventions;

use DFPA\Decisions\Decision;

class OsNotificationIntervention implements InterventionInterface
{
    public function supports(Decision $decision): bool
    {
        return in_array($decision->action, [
            'SUGGEST_FOCUS_LOCK',
            'SUGGEST_PAUSE'
        ]);
    }

    public function execute(Decision $decision): void
    {
        $message = match ($decision->action) {
            'SUGGEST_FOCUS_LOCK' => 'Consider locking focus for a few minutes.',
            'SUGGEST_PAUSE'     => 'You may want to pause and reassess.',
            default             => 'DFPA suggestion'
        };

        if (stripos(PHP_OS, 'WIN') === 0) {
            shell_exec(
                'powershell -command "New-BurntToastNotification -Text \'DFPA\', \'' . $message . '\'"'
            );
        } elseif (stripos(PHP_OS, 'DAR') === 0) {
            shell_exec(
                'osascript -e \'display notification "' . $message . '" with title "DFPA"\''
            );
        } else {
            shell_exec('notify-send "DFPA" "' . $message . '"');
        }
    }
}
