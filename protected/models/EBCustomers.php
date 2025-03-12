<?php

class EBCustomers extends CFormModel
{

    CONST TYPE_INDIVIDUAL = 0; //cá nhân
    CONST TYPE_ENTERPRISE = 1; //doanh nghiệp

    public $name;
    public $code;
    public $tax_code;
    public $address;
    public $bank_account_name;
    public $bank_name;
    public $bank_number;
    public $email;
    public $fax;
    public $phone;
    public $contact_person;
    public $represent_person;
    public $cus_type;

    public function XMLNodeLabel($attribute = null)
    {
        $labels =  array(
            'name'              => 'Name',
            'code'              => 'Code',
            'tax_code'          => 'TaxCode',
            'address'           => 'Address',
            'bank_account_name' => 'BankAccountName',
            'bank_name'         => 'BankName',
            'bank_number'       => 'BankNumber',
            'email'             => 'Email',
            'fax'               => 'Fax',
            'phone'             => 'Phone',
            'contact_person'    => 'ContactPerson',
            'represent_person'  => 'RepresentPerson',
            'cus_type'          => 'CusType',
        );
        if(!empty($attribute)){
            return $labels[$attribute];
        }else{
            return $labels;
        }
    }


    /**
     * @param $models EBCustomers[]
     * @return string
     */
    public static function parserToXml($models){
        $xml = "<Customers>";
        foreach ($models as $model){
            $xml.= "<Customer>";
            foreach ($model->attributes as $attribute_name => $attribute_value){
                $xml.= "<".$model->XMLNodeLabel($attribute_name).">" . $attribute_value . "</".$model->XMLNodeLabel($attribute_name).">";
            }
            $xml.= "</Customer>";
        }
        $xml.= "</Customers>";

        return $xml;
    }



}