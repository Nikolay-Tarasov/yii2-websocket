<?php

namespace app\daemons;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class SocketServer implements MessageComponentInterface {

    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {

        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        $data = json_decode($msg, TRUE);
        if (isset($data['product_id'])) {
            $model = \app\models\Products::find()->where(['id' => $data['product_id']])->one();
            $model->price = $model->price + 10;
            if ($model->save())
                echo "data save to MySQL DB - new_price: $model->price || id: $model->id \n";
            foreach ($this->clients as $client) {
                $client->send($model->price . " | " . $model->id);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

}
