<?php
abstract class Ammunition {

    private $minDamage;
    private $maxDamage;
    private $exploded = false;
    public $description = "Ammunition";
    
    function _construct($minDamage, $maxDamage){
        
        $this->description = "Torpedo";
        $this->minDamage = $minDamage;
        $this->maxDamage = $maxDamage;
    }

    function getDamagePoints(){
    
        if(!$this->exploded)
            return rand($this->minDamage, $this->maxDamage);
        
        $this->exploded = true;
    }
}
abstract class Torpedo extends Ammunition{
    
    
    function _construct($minDamage, $maxDamage){
        
        parent::_construct($minDamage, $maxDamage);
        $this->description = "Torpedo";
    }

    // function strike(){

    //     return $this->explode();
    // }
}
class ClassATorpedo extends Torpedo {

    function __construct(){

        parent::_construct(1,5);
        $this->description = "Class A torpedo";
    }
}
class ClassBTorpedo extends Torpedo {

    function __construct(){
        
        parent::_construct(7,14);
        $this->description = "Class B torpedo";
    }
}
class ClassCTorpedo extends Torpedo {

    function __construct(){
        
        parent::_construct(9,22);
        $this->description = "Class C torpedo";
    }
}