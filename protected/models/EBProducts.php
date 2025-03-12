<?php

class EBProducts extends CFormModel
{

    public $prod_name;
    public $prod_unit;
    public $prod_quantity;
    public $prod_price;
    public $amount;

    public function XMLNodeLabel($attribute = null)
    {
        $labels =  array(
            'prod_name'     => 'ProdName',
            'prod_unit'     => 'ProdUnit',
            'prod_quantity' => 'ProdQuantity',
            'prod_price'    => 'ProdPrice',
            'amount'        => 'Amount',
        );
        if(!empty($attribute)){
            return $labels[$attribute];
        }else{
            return $labels;
        }
    }


    /**
     * @param $models EBProducts[]
     * @return string
     */
    public static function parserToXml($models){
        $xml = "<Products>";
        foreach ($models as $model){
            $xml.= "<Product>";
            foreach ($model->attributes as $attribute_name => $attribute_value){
                $xml.= "<".$model->XMLNodeLabel($attribute_name).">" . $attribute_value . "</".$model->XMLNodeLabel($attribute_name).">";
            }
            $xml.= "</Product>";
        }
        $xml.= "</Products>";

        return $xml;
    }



}