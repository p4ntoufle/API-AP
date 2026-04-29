<?php

// Test if SessionMiddleware is working
session_start();
$_SESSION['test'] = 'working';

echo "Session ID: " . session_id() . "\n";
echo "Session data: " . print_r($_SESSION, true) . "\n";
echo "Headers sent: " . json_encode(headers_list()) . "\n";
