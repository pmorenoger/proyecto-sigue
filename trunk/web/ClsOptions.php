<?php

class ClsOptions {
    var $desarrollo;
    
    
    function ClsOptions(){
        $this->desarrollo = true;
        
    }
    function is_desarrollo(){
        return $this->desarrollo;        
    }
}
?>
