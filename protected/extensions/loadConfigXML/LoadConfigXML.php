<?php
class LoadConfigXML{
    public $xml_path;
    public $xml_name;
    public $xml;
    
    public function __construct($xml_path, $xml_name){
        if(!is_null($xml_path))
            $this->xml_path = $xml_path;
        else
            $this->xml_path = "";
        if(!is_null($xml_name))
            $this->xml_name = $xml_name;
        else
            $this->xml_name = "";
        $xmlFile = $this->xml_path.'/'.$this->xml_name;
        $xml = simplexml_load_file($xmlFile);
        $this->xml = $xml;
    }
    
    public function parse($module, $attrs){
        foreach($this->xml->children() as $child){
            if($child->getName() == $module){
                foreach($child->children() as $attr){
                    if($attr->getName() == $attrs){
                        return $attr;
                    }
                }
            }
           
        }
        return false;
    }
}
?>