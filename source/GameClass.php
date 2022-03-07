<?php
require ("AmmunitionClass.php");
require ("SubmarineClass.php");

if(!defined('STDIN')){
    define('STDIN', fopen('php://stdin','r+'));
}

class Game {
    
    private $floatRange = [1, 45];
    private $diveRange = [5, 75];
    private $inputCb;
    private $outputCb;
    private $endCb;
    private $mySubmarine;
    private $enemySubmarines = [];
    public $inputValue = null;
    // private $playLog = [];
    private $attackLog = [];

    function __construct(callable $inputCb, callable $outputCb, callable $endCb = null)
    {
        $this->inputCb = $inputCb;
        $this->outputCb = $outputCb;
        $this->endCb = $endCb;
        $this->init();
    }
    
    private function init(){

        $this->mySubmarine = new OhioSubmarine(["42°40'04.4\"N", "175°35'28.3\”W"], null);
        
        $this->enemySubmarines[] = new Submarine('west', new ClassATorpedo);
        $this->enemySubmarines[] = new Submarine('north', new ClassCTorpedo);
        $this->enemySubmarines[] = new Submarine('east', new ClassBTorpedo);
    }
    
    public function run (){

        $this->submergeSubmarine($this->mySubmarine);

        foreach ($this->enemySubmarines as $k => $attackerSubmarine) {

            $captainCommand = $this->prepareForDefence($this->mySubmarine, $attackerSubmarine);
            [$enemyCommand, $damagePotential] = $attackerSubmarine->prepareAttack();
            

            $this->runAttack($this->mySubmarine, $attackerSubmarine, $enemyCommand, $k, $damagePotential, $captainCommand);
        }

        $this->getStatistics($this->mySubmarine);
        $this->end();
    }

    private function submergeSubmarine(Submarine $submarine){

        $this->sendOutput("*** Let's play the Seawolf challenge ***\n\nLet's submerge. Which depth should we dive to captain?\n");
        $units = null;

        while(!is_numeric($units)){

            $units = $this->getInput();
        }

        $submarine->dive($units);
    }
    private function prepareForDefence(Submarine $assaultedSubmarine, Submarine $attackerSubmarine){
        
        $captainCommand = null;

        $this->sendOutput("We are being attacked from ". $attackerSubmarine->getPosition(). ", what should we do captain?\n Enter:\n 1. to dive \n 2. to float \n\n");
        
        while (!in_array($captainCommand, [1,2])) {
            
            $captainCommand = $this->getInput();
        }
        
        switch ($captainCommand) {
            case '1':
                $msg = $assaultedSubmarine->dive($this->getDiveDistance());
                $this->sendOutput($msg);
            break;
            case '2':
                $msg = $assaultedSubmarine->float($this->getFloatDistance());
                $this->sendOutput($msg);
            break;
        }

        return $captainCommand;
    }
    private function runAttack(Submarine $asaultedSubmarine, Submarine $attackerSubmarine, int $enemyCommand, int $attackNo, int $damage, int $captainCommand){

        if($enemyCommand == $captainCommand){
                
            $this->sendOutput("Correct ! You dodged the hit.\n");
        }
        else {
            
            $asaultedSubmarine->takeDamage($damage);
            $this->logAttack("Attack #".($attackNo+1)." was successfull. You've been hit by a ".$attackerSubmarine->ammunition->description." and took ".$asaultedSubmarine->getDamage()." damage.");
            
            $this->sendOutput("Incorrect :( You've taken ".$asaultedSubmarine->getDamage()." damage points. You'been hit by a ". $attackerSubmarine->ammunition->description."\n");
        }

        $this->sendOutput("Your current depth is ". $asaultedSubmarine->getDepth().".\n\n");
    }

    private function getStatistics(Submarine $submarine){

        if($submarine->getDamage() == 0){
            $this->sendOutput("You are a victorious lucky Captain!.\n");
        }
        else{
            $this->sendOutput( "Attack log:\n\n");
            
            foreach($this->attackLog as $log){
                
                $this->sendOutput( "$log \n");
            }
            $this->sendOutput( "Better luck next time captain.\n");
        }
    }

    public function getDiveDistance(){

        return rand($this->diveRange[0], $this->diveRange[1]);
    }
    public function getFloatDistance(){

        return rand($this->floatRange[0], $this->floatRange[1]);
    }
    private function getInput(){

        return ($this->inputCb)();
    }
    private function sendOutput($msg){

        ($this->outputCb)($msg);

    }
    private function end(){

        $this->sendOutput("\n\n*** End of the game ***\n\n");
        $this->endCb && ($this->endCb)();
    }
    private function logAttack($msg){

        $this->attackLog[] = $msg;
    }
}
