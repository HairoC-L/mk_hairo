<?php
include_once("routeros_api.class.php");

function conectarMikrotik($ip, $username, $password, $puerto = 8728) {
    $API = new RouterosAPI();
    $API->debug = false;
    $API->port = $puerto;

    if ($API->connect($ip, $username, $password)) {
        // Obtener el nombre del Mikrotik
        $API->write('/system/identity/print');
        $response = $API->read();
        $name = $response[0]['name'];
        $API->disconnect();
        return ['success' => true, 'name' => $name];
    } else {
        return ['success' => false];
    }
}
?>
