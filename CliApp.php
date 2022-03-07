<?php

require('source/GameClass.php');


if(!defined('STDIN')){
    define('STDIN', fopen('php://stdin','r'));
}

try {
    $inputCb = function(){ return fread(STDIN, 5);};
    $outputCb = function($msg){ echo $msg;};
    
    $game = new Game( 
        $inputCb,
        $outputCb
    );
    
    $game->run();
    
} catch (\Throwable $th) {
    print_r($th->getMessage());
}