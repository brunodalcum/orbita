<?php
require_once "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

echo "APP_KEY: " . config("app.key") . "\n";
echo "SESSION_DRIVER: " . config("session.driver") . "\n";
echo "CSRF Token: " . csrf_token() . "\n";
echo "Session ID: " . session_id() . "\n";
?>