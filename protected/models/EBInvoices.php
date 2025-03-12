<?php

class EBInvoices extends CFormModel
{
    public $key;
    public $cus_code;
    public $cus_name;
    public $cus_address;
    public $cus_phone;
    public $cus_tax_code;
    public $payment_method;
    public $kind_of_service;
    public $products;
    public $total;
    public $discount_amount;
    public $vat_rate;
    public $vat_amount;
    public $amount;
    public $amount_in_words;
    public $teller;

    const FREEDOO_INV_KEY = '11';

    public function XMLNodeLabel($attribute = null)
    {
        $labels =  array(
            'key'              => 'key',
            'cus_code'         => 'CusCode',
            'cus_name'         => 'CusName',
            'cus_address'      => 'CusAddress',
            'cus_phone'        => 'CusPhone',
            'cus_tax_code'     => 'CusTaxCode',
            'payment_method'   => 'PaymentMethod',
            'kind_of_service'  => 'KindOfService',
            'products'         => 'Products',
            'total'            => 'Total',
            'discount_amount'  => 'DiscountAmount',
            'vat_rate'         => 'VATRate',
            'vat_amount'       => 'VATAmount',
            'amount'           => 'Amount',
            'amount_in_words'  => 'AmountInWords',
            'teller'           => 'Teller',
        );
        if(!empty($attribute)){
            return $labels[$attribute];
        }else{
            return $labels;
        }
    }


    /**
     * @param $models EBInvoices[]
     *
     * @return string
     */
    public static function parserToXml($models){
        $xml = "<Invoices>";
        foreach ($models as $model){
            $xml.= "<Inv>";
            $xml.= "<key>" . $model->key . "</key>";
            $xml.= "<Invoice>";

            foreach ($model->attributes as $attribute_name => $attribute_value){
                if($attribute_name != 'key'){
                    if($attribute_name == 'products'){
                        $xml.= EBProducts::parserToXml($model->products);
                    }else{
                        $xml.= "<".$model->XMLNodeLabel($attribute_name).">" . $attribute_value . "</".$model->XMLNodeLabel($attribute_name).">";
                    }
                }
            }
            $xml.= "</Invoice>";
            $xml.= "</Inv>";
        }
        $xml.= "</Invoices>";

        return $xml;
    }


    public static  function parserAdjustToXml($model, $fkey, $type = 4){
        $xml = "<AdjustInv>";
        $xml.= "<key>" . $fkey . "</key>";
        $xml.= "<Type>".$type."</Type>";
        foreach ($model->attributes as $attribute_name => $attribute_value){
            if($attribute_name != 'key' && $attribute_name != 'discount_amount'){
                if($attribute_name == 'products'){
                    $xml.= EBProducts::parserToXml($model->products);
                }else{
                    $xml.= "<".$model->XMLNodeLabel($attribute_name).">" . $attribute_value . "</".$model->XMLNodeLabel($attribute_name).">";
                }
            }
        }
        $xml.= "</AdjustInv>";

        return $xml;
    }


}