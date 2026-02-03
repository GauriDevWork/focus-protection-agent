<?php
date_default_timezone_set('Asia/Kolkata');
// ==========================
// DFPA Runner (Production)
// ==========================

// Core
require_once __DIR__ . '/src/Events/Event.php';
require_once __DIR__ . '/src/Signals/Signal.php';

// Analyzer & Decision
require_once __DIR__ . '/src/Analyzer/SignalAnalyzer.php';
require_once __DIR__ . '/src/Decisions/Decision.php';
require_once __DIR__ . '/src/Decisions/DecisionEngine.php';

// Sensors
require_once __DIR__ . '/src/Sensors/SensorInterface.php';
require_once __DIR__ . '/src/Sensors/GitSensor.php';
require_once __DIR__ . '/src/Sensors/ActivitySensor.php';
require_once __DIR__ . '/src/Sensors/BasecampSensor.php';

// Interventions
require_once __DIR__ . '/src/Interventions/InterventionInterface.php';
require_once __DIR__ . '/src/Interventions/CliIntervention.php';
require_once __DIR__ . '/src/Interventions/OsNotificationIntervention.php';
require_once __DIR__ . '/src/Interventions/InterventionDispatcher.php';

// Agent & Utils
require_once __DIR__ . '/src/Agent/DFPAAgent.php';
require_once __DIR__ . '/src/Utils/Logger.php';

use DFPA\Agent\DFPAAgent;
use DFPA\Sensors\GitSensor;
use DFPA\Sensors\ActivitySensor;
use DFPA\Sensors\BasecampSensor;

// --------------------------
// Agent Setup
// --------------------------
$agent = new DFPAAgent();

$agent->registerSensor(new GitSensor());
$agent->registerSensor(new ActivitySensor());
$agent->registerSensor(new BasecampSensor());

// --------------------------
// Run DFPA (Background Mode)
// --------------------------
while (true) {
    $result = $agent->run();

    // Optional: rare CLI visibility for decisions
    if ($result['decision']) {
        echo "[DFPA] {$result['decision']->action}\n";
    }

    sleep(60); // run once per minute
}
