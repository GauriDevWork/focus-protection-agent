<?php

require_once __DIR__ . '/src/Events/Event.php';
require_once __DIR__ . '/src/Sensors/SensorInterface.php';
require_once __DIR__ . '/src/Sensors/GitSensor.php';
require_once __DIR__ . '/src/Sensors/ActivitySensor.php';
require_once __DIR__ . '/src/Sensors/BasecampSensor.php';
require_once __DIR__ . '/src/Agent/DFPAAgent.php';
require_once __DIR__ . '/src/Utils/Logger.php';
require_once __DIR__ . '/src/Signals/Signal.php';
require_once __DIR__ . '/src/Analyzer/SignalAnalyzer.php';
require_once __DIR__ . '/src/Decisions/Decision.php';
require_once __DIR__ . '/src/Decisions/DecisionEngine.php';

use DFPA\Agent\DFPAAgent;
use DFPA\Sensors\GitSensor;
use DFPA\Sensors\ActivitySensor;
use DFPA\Sensors\BasecampSensor;
use DFPA\Utils\Logger;

$agent = new DFPAAgent();

$agent->registerSensor(new GitSensor());
$agent->registerSensor(new ActivitySensor());
$agent->registerSensor(new BasecampSensor());

// $result = $agent->run();

// Logger::log($result['signals']);

// if ($result['decision']) {
//     echo "DECISION → {$result['decision']->action}\n";
// }

for ($i = 1; $i <= 10; $i++) {
    echo "\n--- RUN {$i} ---\n";

    $result = $agent->run();

    echo "SIGNALS: " . count($result['signals']) . PHP_EOL;
    Logger::log($result['signals']);

    if ($result['decision']) {
        echo "DECISION → {$result['decision']->action}\n";
    }

    sleep(1);
}
