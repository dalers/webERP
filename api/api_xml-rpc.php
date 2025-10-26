<?php
$path = __DIR__ . '/includes/api_xml-rpc_definition.php';
echo 'the path is: '. $path;
exit;
/**
 * Entrypoint for all xml-rpc calls (the only file that actually has to be accessible from the web)
 */

$dispatchMap = include(__DIR__ . '/includes/api_xml-rpc_definition.php');

$server = new \PhpXmlRpc\Server($dispatchMap, false);
$server->service();
