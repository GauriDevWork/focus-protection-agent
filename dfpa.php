<?php

require_once __DIR__ . '/src/Events/Event.php';
require_once __DIR__ . '/src/Sensors/SensorInterface.php';
require_once __DIR__ . '/src/Sensors/GitSensor.php';
require_once __DIR__ . '/src/Sensors/ActivitySensor.php';
require_once __DIR__ . '/src/Sensors/BasecampSensor.php';
require_once __DIR__ . '/src/Agent/DFPAAgent.php';
require_once __DIR__ . '/src/Utils/Logger.php';

use DFPA\Agent\DFPAAgent;
use DFPA\Sensors\GitSensor;
use DFPA\Sensors\ActivitySensor;
use DFPA\Sensors\BasecampSensor;
use DFPA\Utils\Logger;

$agent = new DFPAAgent();

$agent->registerSensor(new GitSensor());
$agent->registerSensor(new ActivitySensor());
$agent->registerSensor(new BasecampSensor());

$events = $agent->run();
Logger::log($events);
