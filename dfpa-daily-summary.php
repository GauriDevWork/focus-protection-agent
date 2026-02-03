<?php

// ==========================
// DFPA Daily Summary Script
// ==========================

// Date to summarize (default: today)
$date = $argv[1] ?? date('Y-m-d');

$logFile = __DIR__ . "/logs/dfpa-{$date}.log";
$outputFile = __DIR__ . "/logs/summary-{$date}.txt";
ob_start();


if (!file_exists($logFile)) {
    echo "No DFPA log found for {$date}.\n";
    exit;
}

$lines = file($logFile, FILE_IGNORE_NEW_LINES);

$events = [];
$signals = [];
$decisions = [];

foreach ($lines as $line) {
    if (str_contains($line, ' EVENT ')) {
        $events[] = $line;
    } elseif (str_contains($line, ' SIGNAL ')) {
        $signals[] = $line;
    } elseif (str_contains($line, ' DECISION ')) {
        $decisions[] = $line;
    }
}

// --------------------------
// Output Report
// --------------------------
echo PHP_EOL;
echo "==========================================" . PHP_EOL;
echo " DFPA Daily Summary — {$date}" . PHP_EOL;
echo "==========================================" . PHP_EOL;

// -------- Events --------
echo PHP_EOL . "🧠 Observed Events" . PHP_EOL;
echo "------------------------------------------" . PHP_EOL;

if (empty($events)) {
    echo "No notable events recorded." . PHP_EOL;
} else {
    foreach ($events as $event) {
        echo formatLine($event);
    }
}

// -------- Signals --------
echo PHP_EOL . "🔍 Detected Patterns (Signals)" . PHP_EOL;
echo "------------------------------------------" . PHP_EOL;

if (empty($signals)) {
    echo "No significant patterns detected." . PHP_EOL;
} else {
    foreach ($signals as $signal) {
        echo formatLine($signal);
    }
}

// -------- Decisions --------
echo PHP_EOL . "🗣 Decisions & Interventions" . PHP_EOL;
echo "------------------------------------------" . PHP_EOL;

if (empty($decisions)) {
    echo "No interventions were necessary today." . PHP_EOL;
} else {
    foreach ($decisions as $decision) {
        echo formatLine($decision);
    }
}

echo PHP_EOL;
echo "==========================================" . PHP_EOL;
echo " End of Report" . PHP_EOL;
echo "==========================================" . PHP_EOL;

$report = ob_get_clean();
file_put_contents($outputFile, $report);

echo "DFPA daily summary generated: {$outputFile}\n";
// --------------------------
// Helper Function
// --------------------------
function formatLine(string $line): string
{
    // Example:
    // [10:47] SIGNAL EXTERNAL_INTERRUPTION confidence=0.6 {"notifications":3}

    preg_match('/\[(.*?)\]\s+(EVENT|SIGNAL|DECISION)\s+(.*)/', $line, $matches);

    $time = $matches[1] ?? '--:--';
    $type = $matches[2] ?? 'UNKNOWN';
    $details = $matches[3] ?? '';

    return "• {$time} | {$type} | {$details}" . PHP_EOL;
}
