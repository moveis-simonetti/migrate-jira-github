<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/functions.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

define('USERS', include_once __DIR__ . '/configs/users.php');
define('RESOLUTIONS_MAP', include_once __DIR__ . '/configs/resolutions-map.php');
define('RESOLUTIONS', array_keys(RESOLUTIONS_MAP));
define('STATUS_MAP', include_once __DIR__ . '/configs/status-map.php');
define('ISSUETYPES_MAP', include_once __DIR__ . '/configs/issuetypes-map.php');