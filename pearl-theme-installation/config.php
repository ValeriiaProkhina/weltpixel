<?php

/** @var  $bootstrapPath */
$bootstrapPath = __DIR__ . '/../app/bootstrap.php';
if (!file_exists($bootstrapPath)) {
    // project path is used from the pub directory
    $bootstrapPath = __DIR__ . '/../../app/bootstrap.php';
}

/** CLI workaround */
$args = [];
parse_str($_SERVER['QUERY_STRING'], $args);
$_SERVER['argv'] = $args;