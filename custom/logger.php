<?php

// https://www.loggly.com/ultimate-guide/php-logging-basics/
// --------------------------------------------------------
// USAGE
// --------------------------------------------------------
// wicket_logger("%file% %level% %message%", ["level" => "NOTICE", "message" => "my message goes here", "file" => __FILE__.':'.__LINE__], 'fusebill.log');
// --------------------------------------------------------
// COMMON LEVEL TYPES:
// --------------------------------------------------------
// NOTICE, ERROR, WARNING
// --------------------------------------------------------
$uniqid = 'requestid='.md5(uniqid(rand(), true)).' ';
function wicket_logger($message, array $data, $logFile = "error.log"){
  global $uniqid;
  $date = new DateTime();
  $date = $date->format("Y-m-d H:i:s") . ' ';
  foreach ($data as $key => $val) {
    $message = str_replace("%{$key}%", $val, $message);
  }
  $message = $date.$uniqid.$message;
  $message .= PHP_EOL;
  return file_put_contents($logFile.'-05f9edd7007b0d289d56ac83d79c08e1', $message, FILE_APPEND);
}
