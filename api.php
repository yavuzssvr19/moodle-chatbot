<?php
define('AJAX_SCRIPT', true);
// chatbot popup pencerende AJAX ile çalışan sistem
require_once(__DIR__ . '/../../config.php');
require_login(); 
header('Content-Type: text/html; charset=utf-8');

$query = $_POST['query'] ?? '';
if (!$query) {
    http_response_code(400);
    echo "Soru gelmedi.";
    exit;
}
putenv("GOOGLE_API_KEY=AIzaSyAyHrG566VKpTroXOIm3aYumeXQ8mS2KOQ");
$escaped = escapeshellarg($query);
$python = '/home/yavuzsssvr/chatbotenv/bin/python';
$script = '/var/www/html/moodle/local/chatbot/chatbot.py';
$cmd = "$python $script $escaped";

$output = shell_exec($cmd);

if (!$output) {
    echo "[HATA] Boş çıktı geldi veya script çalışmadı.";
} else {
    echo $output;
}

global $DB, $USER;

$record = new stdClass();
$record->userid = $USER->id;
$record->question = $query;
$record->answer = $output;
$record->timecreated = time();

$DB->insert_record('chatbot_logs', $record);
