<?php
header('Content-Type: application/json; charset=utf-8');

error_reporting(E_ALL);
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/send_errors.log');

$BOT_TOKEN = 'PASTE_BOT_TOKEN_HERE';
$CHAT_ID   = '547370288'; // личка; для группы/канала обычно -100...

function respond($ok, $msg, $extra = [], $code = 200) {
  http_response_code($code);
  echo json_encode(array_merge(['ok'=>$ok,'message'=>$msg], $extra), JSON_UNESCAPED_UNICODE);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond(false, 'Method not allowed', [], 405);
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) $data = $_POST;

$hp = trim($data['company'] ?? '');
if ($hp !== '') respond(true, 'ok');

$name  = trim($data['name'] ?? '');
$phone = trim($data['phone'] ?? '');
$svc   = trim($data['svc'] ?? '');
$msg   = trim($data['msg'] ?? '');

if (mb_strlen($phone) < 6) respond(false, 'Укажите телефон', [], 400);

$name  = mb_substr($name, 0, 80);
$phone = mb_substr($phone, 0, 40);
$svc   = mb_substr($svc, 0, 80);
$msg   = mb_substr($msg, 0, 1200);

$text = "🧰 Заявка с сайта TOP174\n"
      . "👤 Имя: {$name}\n"
      . "📞 Телефон: {$phone}\n"
      . "🧱 Направление: {$svc}\n"
      . "📝 ТЗ/Комментарий:\n{$msg}\n"
      . "🌐 IP: " . ($_SERVER['REMOTE_ADDR'] ?? '-') . "\n"
      . "🕒 " . date('Y-m-d H:i:s');

$api = "https://api.telegram.org/bot{$BOT_TOKEN}/sendMessage";
$postFields = http_build_query([
  'chat_id' => $CHAT_ID,
  'text' => $text,
  'disable_web_page_preview' => true
]);

if (function_exists('curl_init')) {
  $ch = curl_init();
  curl_setopt_array($ch, [
    CURLOPT_URL => $api,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $postFields,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CONNECTTIMEOUT => 7,
    CURLOPT_TIMEOUT => 12,
  ]);
  $result = curl_exec($ch);
  $err    = curl_error($ch);
  $code   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  if ($result === false) respond(false, 'curl error', ['details'=>$err], 500);
  if ($code < 200 || $code >= 300) respond(false, 'telegram error', ['http'=>$code,'resp'=>$result], 502);
  respond(true, 'sent');
}

$ctx = stream_context_create([
  'http' => [
    'method'  => 'POST',
    'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
    'content' => $postFields,
    'timeout' => 12
  ]
]);

$result = @file_get_contents($api, false, $ctx);
if ($result === false) respond(false, 'http request failed (no curl)', [], 500);

respond(true, 'sent');
