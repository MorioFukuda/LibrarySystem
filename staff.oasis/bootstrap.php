<?php

require dirname(__FILE__) . '/core/ClassLoader.php';

$loader = new ClassLoader();
$loader->registerDir(dirname(__FILE__) . '/core');
$loader->registerDir(dirname(__FILE__) . '/../oasis/models');
$loader->register();
