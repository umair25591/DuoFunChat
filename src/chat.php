<?php
namespace MyApp;
require __DIR__ . '/../source/php/functions.php';
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;
    protected $waitingClients;
    protected $waitingRooms; // New array to handle room-based connections
    protected $playAgain;
    protected $userStatus;
    protected $dbConn;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->waitingClients = [];
        $this->waitingRooms = []; // Initialize the new array
        $this->playAgain = [];
        $this->userStatus = [];
        $this->dbConn = require __DIR__ . '/../source/php/db-connection.php';
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        
        $queryString = $conn->httpRequest->getUri()->getQuery();
        parse_str($queryString, $queryParams);

        $conn->type = isset($queryParams['type']) ? $queryParams['type'] : null;
        $conn->userId = isset($queryParams['userId']) ? $queryParams['userId'] : null;
        $conn->roomId = isset($queryParams['roomId']) ? $queryParams['roomId'] : null; // New roomId parameter

        if ($conn->userId) {
            $status = isset($queryParams['status']) ? $queryParams['status'] : null;
            if (!empty($status)) {
                $this->userStatus[$conn->userId] = $status;
                $this->updateUserStatus($conn->userId, $status);
            }
        }

        if ($conn->type) {
            $this->waitingClients[$conn->resourceId] = $conn;
            $this->pairClient($conn);
        }

        $this->logClients();
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);

        if (isset($data['playagain']) && $data['playagain'] === "Opponent wants to play again") {
            $partner = $from->pairedWith;
            $partner->send($msg);
            $this->playAgain[$from->resourceId] = true;
            $this->checkPlayAgainRequest($from);
        } else {
            if (isset($from->pairedWith)) {
                $partner = $from->pairedWith;
                $partner->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        unset($this->waitingClients[$conn->resourceId]);
    
        if (isset($conn->pairedWith)) {
            $partner = $conn->pairedWith;
            unset($conn->pairedWith);
            $partner->send(json_encode(['connection' => 'CoNneCtIoN ClOsEd']));
        }
    
        // Check if the user status exists and update it
        if (isset($conn->userId) && isset($this->userStatus[$conn->userId])) {
            unset($this->userStatus[$conn->userId]);
            $this->updateUserStatus($conn->userId, 'offline');
        }
    
        echo "Connection {$conn->resourceId} has disconnected\n";
        $this->logClients();
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    protected function checkPlayAgainRequest(ConnectionInterface $client) {
        if (isset($client->pairedWith) && isset($this->playAgain[$client->resourceId]) && isset($this->playAgain[$client->pairedWith->resourceId])) {
            $partner = $client->pairedWith;

            unset($this->playAgain[$client->resourceId]);
            unset($this->playAgain[$partner->resourceId]);

            $client->send(json_encode(['action' => 'playagain']));
            $partner->send(json_encode(['action' => 'playagain']));

            echo "Clients {$client->resourceId} and {$partner->resourceId} both want to play again\n";
        }
    }

    protected function pairClient(ConnectionInterface $client) {
        if ($client->type === null) {
            return;
        }

        foreach ($this->waitingClients as $resourceId => $waitingClient) {
            if($resourceId !== $client->resourceId && $client->type === $waitingClient->type && $client->roomId && $waitingClient->roomId){
                if($client->roomId === $waitingClient->roomId){
                    $this->createPair($client, $waitingClient);
                    break;
                }
            }
            elseif ($resourceId !== $client->resourceId && $client->type === $waitingClient->type) {
                if ($client->userId && $waitingClient->userId) {
                    $this->createPairWithId($client, $waitingClient);
                    break;
                } elseif (!$client->userId && !$waitingClient->userId) {
                    $this->createPair($client, $waitingClient);
                    break;
                }
            }
        }
    }

    protected function createPair($client, $waitingClient) {
        $client->pairedWith = $waitingClient;
        $waitingClient->pairedWith = $client;

        unset($this->waitingClients[$client->resourceId]);
        unset($this->waitingClients[$waitingClient->resourceId]);

        $client->send(json_encode(['action' => 'paired', 'mode' => 'caller', 'partnerId' => $waitingClient->resourceId]));
        $waitingClient->send(json_encode(['action' => 'paired', 'mode' => 'receiver', 'partnerId' => $client->resourceId]));

        echo "Paired client {$client->resourceId} with {$waitingClient->resourceId}\n";
    }

    protected function createPairWithId($client, $waitingClient) {
        $client->pairedWith = $waitingClient;
        $waitingClient->pairedWith = $client;

        unset($this->waitingClients[$client->resourceId]);
        unset($this->waitingClients[$waitingClient->resourceId]);

        // Ensure userId is not null
        $clientUserId = $client->userId ? $client->userId : 'null';
        $waitingClientUserId = $waitingClient->userId ? $waitingClient->userId : 'null';

        $client->send(json_encode(['action' => 'paired', 'mode' => 'caller', 'partnerId' => $waitingClientUserId]));
        $waitingClient->send(json_encode(['action' => 'paired', 'mode' => 'receiver', 'partnerId' => $clientUserId]));

        echo "Paired client {$client->resourceId} (User ID: $clientUserId) with {$waitingClient->resourceId} (User ID: $waitingClientUserId)\n";
    }

    protected function logClients() {
        echo "All clients:\n";
        foreach ($this->clients as $client) {
            echo " - Client {$client->resourceId}\n";
        }

        echo "Waiting clients:\n";
        foreach ($this->waitingClients as $waitingClient) {
            echo " - Waiting client {$waitingClient->resourceId}\n";
        }

        echo "Waiting rooms:\n";
            foreach ($this->waitingRooms as $roomId => $waitingRoomClients) {
                echo "Room ID: {$roomId}\n";
                foreach ($waitingRoomClients as $waitingClient) {
                    echo " - Waiting client {$waitingClient->resourceId}\n";
                }
            }
    }

    protected function updateUserStatus($userId, $status) {
        updateStatusInDatabase($this->dbConn, $userId, $status);
    }
}