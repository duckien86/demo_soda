<?php

class EBAdjustInv extends EBInvoices
{
    CONST TYPE_ADJUST_INCREASE = 2; //điều chỉnh tăng
    CONST TYPE_ADJUST_REDUCE = 3; //điều chỉnh giảm
    CONST TYPE_ADJUST_INFORMATION = 4; //điều chỉnh tăng


    public $type = self::TYPE_ADJUST_INCREASE;


    public function XMLNodeLabel($attribute = null)
    {
        $labels = array(
            'key'              => 'key',
            'cus_code'         => 'CusCode',
            'cus_name'         => 'CusName',
            'cus_address'      => 'CusAddress',
            'cus_phone'        => 'CusPhone',
            'cus_tax_code'     => 'CusTaxCode',
            'payment_method'   => 'PaymentMethod',
            'kind_of_service'  => 'KindOfService',
            'type'             => 'Type',
            'products'         => 'Products',
            'total'            => 'Total',
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
     * @param $model EBAdjustInv
     * @param $key string - Giá trị khóa để phân biệt hóa đơn xuất cho khách hàng nào
     *
     * @return string
     */
    public static function parserToXml($model){
        $xml= "<AdjustInv>";
        foreach ($model->attributes as $attribute_name => $attribute_value){
            if($attribute_name == 'products'){
                $xml.= EBProducts::parserToXml($model->products);
            }else{
                $xml.= "<".$model->XMLNodeLabel($attribute_name).">" . $attribute_value . "</".$model->XMLNodeLabel($attribute_name).">";
            }
        }
        $xml.= "<AdjustInv>";

        return $xml;
    }



}