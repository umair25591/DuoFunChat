<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\Chat;
use React\Socket\SecureServer;
use React\Socket\Server as ReactServer;
use React\EventLoop\Factory as EventLoopFactory;

require dirname(__DIR__) . '/vendor/autoload.php';

// Define paths to your SSL certificate and key
$sslCert = 'C:/xampp/apache/conf/ssl.crt/server.crt';
$sslKey = 'C:/xampp/apache/conf/ssl.key/server.key';

// Create the event loop
$loop = EventLoopFactory::create();

// Create the WebSocket server
$webSocketServer = new WsServer(new Chat());

// Create the HTTP server
$httpServer = new HttpServer($webSocketServer);

// Create a ReactPHP socket server
$reactSocketServer = new ReactServer('0.0.0.0:8080', $loop);

// Wrap the ReactPHP socket server with SSL
$secureServer = new SecureServer($reactSocketServer, $loop, [
    'local_cert' => $sslCert,
    'local_pk' => $sslKey,
    'allow_self_signed' => true, // Allow self-signed certificates
    'verify_peer' => false
]);

// Create and run the IoServer with SecureServer wrapped around the HTTP server
$server = new IoServer($httpServer, $secureServer, $loop);

$server->run();











