<?php
class Submarine {
    
    private $depth;
    private $position;
    private $damage = 0;
    private $log = [];

    function __construct($position='', Ammunition $ammunition = null)
    {
        $this->ammunition = $ammunition;
        $this->position = $position;
    }

    private function changeDepth(int $units){

        $depth = $this->depth + $units;
        $this->depth = $depth < 0? $depth: 0;
    }
    
    public function dive(int $units = 0){

        $this->changeDepth( -$units );
        $this->addLog("Diving $units m");
    }
    
    public function float(int $units = 0){
        
        $this->changeDepth( $units );
        $this->addLog("Floating $units m");
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function getDepth(){

        return $this->depth;
    }

    public function getDamage(){

        return $this->damage;
    }

    public function takeDamage($units){

        $damage = $this->damage === 0? $units: 2*$units;
        $this->addLog("Took $damage damage");

        return $this->damage += $damage;
    }

    public function getDamagePoints(){

        return $this->ammunition? $this->ammunition->getDamagePoints(): 0;
    }

    public function prepareAttack(){

        $enemyCommand = rand(1,2);
        $potentialDamagePoints = $this->getDamagePoints();

        return [$enemyCommand, $potentialDamagePoints];
    }
    private function addLog($msg){

        $this->log[] = $msg;
    }

    public function getLog(){

        return $this->log;
    }

}

class OhioSubmarine extends Submarine {

    public $description = "Ohaio class submarine";
    
}