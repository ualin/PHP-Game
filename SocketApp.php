<?php
require ('plugins/SocketClass.php');
require ('source/GameClass.php');

$socket = new MySocket();
try {
    
    $game = new Game( 
        function() use($socket){ return $socket->getInput();},
        function($msg) use($socket){ $socket->sendOutput($msg);},
        function() use($socket){ $socket->close();},
    );
    
    $game->run();
    
} catch (\Throwable $th) {
    print_r($th->getMessage());
    $socket->close();
}
