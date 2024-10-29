<?php

class phpMQTT {
    private $socket;
    private $msgid = 1;
    public $keepalive = 10;
    public $timesinceping;
    public $topics = [];
    public $debug = false;
    private $address;
    private $port;
    private $clientid;
    private $username;
    private $password;

    public function __construct($address, $port, $clientid) {
        $this->address = $address;
        $this->port = $port;
        $this->clientid = $clientid;
    }

    public function connect($username = null, $password = null) {
        $this->username = $username;
        $this->password = $password;
        
        // Connect to the MQTT broker
        $this->socket = fsockopen($this->address, $this->port, $errno, $errstr, 60);
        if (!$this->socket) {
            if ($this->debug) echo "Connection failed! Error: $errno - $errstr";
            return false;
        }

        // Send connection request
        $buffer = chr(0x10);
        $buffer .= chr(strlen($this->clientid) + 14); // Remaining length
        $buffer .= "\x00\x04MQTT\x04\x02\x00" . chr($this->keepalive);
        $buffer .= chr(0x00) . chr(strlen($this->clientid)) . $this->clientid;

        fwrite($this->socket, $buffer);

        // Connection success
        if ($this->debug) echo "Connected to broker at {$this->address}:{$this->port}\n";
        return true;
    }

    public function subscribe($topics, $qos = 0) {
        $buffer = chr(0x82) . chr(2 + strlen($topics)) . chr($this->msgid >> 8) . chr($this->msgid & 0xFF);
        $buffer .= chr(0x00) . chr(strlen($topics)) . $topics;
        
        fwrite($this->socket, $buffer);
        if ($this->debug) echo "Subscribed to topic: $topics\n";
    }

    public function publish($topic, $content, $qos = 0) {
        $buffer = chr(0x30) . chr(2 + strlen($topic) + strlen($content));
        $buffer .= chr(0x00) . chr(strlen($topic)) . $topic;
        $buffer .= $content;
        
        fwrite($this->socket, $buffer);
        if ($this->debug) echo "Published message to topic: $topic\n";
    }

    public function close() {
        fclose($this->socket);
        if ($this->debug) echo "Connection closed.\n";
    }
}

?>
