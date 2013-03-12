<?php

require dirname(__FILE__) . '/../bootstrap.php';
require dirname(__FILE__) . '/../StaffOasisApplication.php';

$app = new StaffOasisApplication(true);
$app->run();
