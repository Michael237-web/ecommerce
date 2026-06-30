<?php

require 'db.php';

/*
Safaricom sends payment result here
*/

$data = file_get_contents("php://input");
$log = fopen("mpesa_log.txt", "a");
fwrite($log, $data . PHP_EOL);
fclose($log);

/* OPTIONAL: decode & confirm payment then create order */