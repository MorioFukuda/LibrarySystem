<?php

require dirname(__FILE__) . '/../library-system/core/ClassLoader.php';

$loader = new ClassLoader();
$loader->registerDir(dirname(__FILE__) . '/../library-system/core');
$loader->registerDir(dirname(__FILE__) . '/../library-system/models');
$loader->register();
