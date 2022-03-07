<?php

class MySocket {

    function __construct()
    {
        
        $this->initSocket();
        $this->openForConnections();
        
    }

    private function initSocket(){

        set_time_limit(0); // disable timeout
        ob_implicit_flush(); // disable output caching 
        
        // Settings    
        $address = '127.0.0.1';
        $port = 5000;
        
        
        /*
            function socket_create ( int $domain , int $type , int $protocol )
            $domain can be AF_INET, AF_INET6 for IPV6 , AF_UNIX for Local communication protocol
            $protocol can be SOL_TCP, SOL_UDP  (TCP/UDP)
            @returns true on success
        */
        
        if (($this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
            echo "Couldn't create socket".socket_strerror(socket_last_error())."\n";
        }
        
        
        /*
            socket_bind ( resource $socket , string $address [, int $port = 0 ] )
            Bind socket to listen to address and port
        */
        
        if (socket_bind($this->socket, $address, $port) === false) {
            echo "Bind Error ".socket_strerror(socket_last_error($this->socket)) ."\n";
        }
        
        if (socket_listen($this->socket, 5) === false) {
            echo "Listen Failed ".socket_strerror(socket_last_error($this->socket)) . "\n";
        }
    }

    private function openForConnections(){

        if (($this->msgsock = socket_accept($this->socket)) === false) {
            echo "Error: socket_accept: " . socket_strerror(socket_last_error($this->socket)) . "\n";
        }
    
        /* Send Welcome message. */
        $msg = "\nPHP Websocket \n";
        socket_write($this->msgsock, $msg, strlen($msg));
    }

    function close(){
        
        socket_close($this->msgsock);
        socket_close($this->socket);
    }
    
    function getInput(){
        
        do{
            if (false === ($buf = socket_read($this->msgsock, 2048, PHP_NORMAL_READ))) {
                echo "Error: socket_read: ".socket_strerror(socket_last_error($this->msgsock)) . " \n";
                
                socket_close($this->msgsock);
                $this->openForConnections();
            }
        } while(!trim($buf));

        return trim($buf);
    }
    function sendOutput($msg){

        socket_write($this->msgsock, $msg, strlen($msg));
    }
}
?>