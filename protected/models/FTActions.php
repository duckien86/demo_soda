<?php

/**
 * This is the model class for table "tbl_actions".
 *
 * The followings are the available columns in table 'tbl_actions':
 * @property string $action_id
 * @property string $transaction_id
 * @property string $merchant_id
 * @property string $publisher_id
 * @property integer $campaign_id
 * @property string $click_id
 * @property string $inviter_code
 * @property integer $campaign_category_id
 * @property double $amount
 * @property integer $action_status
 * @property string $created_on
 * @property string $order_code
 * @property string $client_name
 * @property string $client_email
 * @property string $client_address
 * @property string $client_mobile
 * @property string $order_time
 * @property string $note
 * @property string $quantity
 * @property string $product_name
 * @property string $package_code
 * @property string $msisdn
 * @property string $product_id
 * @property double $price
 * @property double $price_discount
 * @property double $total_money
 * @property string $personal_id
 * @property string $personal_photo_font_url
 * @property string $personal_photo_behind_url
 * @property string $extra_params
 * @property integer $type
 * @property string $short_link_id
 * @property string $utm_source
 * @property string $utm_medium
 * @property string $utm_campaign
 * @property string $utm_content
 * @property string $sale_offices_id
 * @property string $vnp_province_id
 * @property string $commissions_type
 * @property integer $yearmonth
 * @property integer $is_scan_total
 * @property integer $process_traffix
 * @property string $active_date
 */
class FTActions extends CActiveRecord
{
    public $active_date;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'tbl_actions';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('action_id, publisher_id, amount, action_status, created_on, order_code, personal_id', 'required'),
            array('campaign_id, campaign_category_id, action_status, type, yearmonth, is_scan_total, process_traffix', 'numerical', 'integerOnly'=>true),
            array('amount, price, price_discount, total_money', 'numerical'),
            array('action_id, transaction_id, publisher_id, click_id', 'length', 'max'=>128),
            array('merchant_id, quantity, sale_offices_id', 'length', 'max'=>11),
            array('inviter_code, package_code, utm_source, vnp_province_id', 'length', 'max'=>255),
            array('order_code, product_name', 'length', 'max'=>1024),
            array('client_name, client_email, client_address, product_id, personal_photo_font_url, personal_photo_behind_url, utm_medium, utm_campaign, utm_content', 'length', 'max'=>512),
            array('client_mobile, msisdn', 'length', 'max'=>20),
            array('personal_id', 'length', 'max'=>60),
            array('short_link_id', 'length', 'max'=>10),
            array('commissions_type', 'length', 'max'=>50),
            array('order_time, note, extra_params , active_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('action_id, transaction_id, merchant_id, publisher_id, campaign_id, click_id, inviter_code, campaign_category_id, amount, action_status, created_on, order_code, client_name, client_email, client_address, client_mobile, order_time, note, quantity, product_name, package_code, msisdn, product_id, price, price_discount, total_money, personal_id, personal_photo_font_url, personal_photo_behind_url, extra_params, type, short_link_id, utm_source, utm_medium, utm_campaign, utm_content, sale_offices_id, vnp_province_id, commissions_type, yearmonth, is_scan_total, process_traffix , active_date', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'action_id' => 'Action',
            'transaction_id' => 'Transaction',
            'merchant_id' => 'Merchant',
            'publisher_id' => 'Publisher',
            'campaign_id' => 'Campaign',
            'click_id' => 'Click',
            'inviter_code' => 'Inviter Code',
            'campaign_category_id' => 'Campaign Category',
            'amount' => 'Amount',
            'action_status' => 'Action Status',
            'created_on' => 'Created On',
            'order_code' => 'Order Code',
            'client_name' => 'Client Name',
            'client_email' => 'Client Email',
            'client_address' => 'Client Address',
            'client_mobile' => 'Client Mobile',
            'order_time' => 'Order Time',
            'note' => 'Note',
            'quantity' => 'Quantity',
            'product_name' => 'Product Name',
            'package_code' => 'Package Code',
            'msisdn' => 'Msisdn',
            'product_id' => 'Product',
            'price' => 'Price',
            'price_discount' => 'Price Discount',
            'total_money' => 'Total Money',
            'personal_id' => 'Personal',
            'personal_photo_font_url' => 'Personal Photo Font Url',
            'personal_photo_behind_url' => 'Personal Photo Behind Url',
            'extra_params' => 'Extra Params',
            'type' => 'Type',
            'short_link_id' => 'Short Link',
            'utm_source' => 'Utm Source',
            'utm_medium' => 'Utm Medium',
            'utm_campaign' => 'Utm Campaign',
            'utm_content' => 'Utm Content',
            'sale_offices_id' => 'Sale Offices',
            'vnp_province_id' => 'Vnp Province',
            'commissions_type' => 'Commissions Type',
            'yearmonth' => 'Yearmonth',
            'is_scan_total' => 'Is Scan Total',
            'process_traffix' => 'Process Traffix',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('action_id',$this->action_id,true);
        $criteria->compare('transaction_id',$this->transaction_id,true);
        $criteria->compare('merchant_id',$this->merchant_id,true);
        $criteria->compare('publisher_id',$this->publisher_id,true);
        $criteria->compare('campaign_id',$this->campaign_id);
        $criteria->compare('click_id',$this->click_id,true);
        $criteria->compare('inviter_code',$this->inviter_code,true);
        $criteria->compare('campaign_category_id',$this->campaign_category_id);
        $criteria->compare('amount',$this->amount);
        $criteria->compare('action_status',$this->action_status);
        $criteria->compare('created_on',$this->created_on,true);
        $criteria->compare('order_code',$this->order_code,true);
        $criteria->compare('client_name',$this->client_name,true);
        $criteria->compare('client_email',$this->client_email,true);
        $criteria->compare('client_address',$this->client_address,true);
        $criteria->compare('client_mobile',$this->client_mobile,true);
        $criteria->compare('order_time',$this->order_time,true);
        $criteria->compare('note',$this->note,true);
        $criteria->compare('quantity',$this->quantity,true);
        $criteria->compare('product_name',$this->product_name,true);
        $criteria->compare('package_code',$this->package_code,true);
        $criteria->compare('msisdn',$this->msisdn,true);
        $criteria->compare('product_id',$this->product_id,true);
        $criteria->compare('price',$this->price);
        $criteria->compare('price_discount',$this->price_discount);
        $criteria->compare('total_money',$this->total_money);
        $criteria->compare('personal_id',$this->personal_id,true);
        $criteria->compare('personal_photo_font_url',$this->personal_photo_font_url,true);
        $criteria->compare('personal_photo_behind_url',$this->personal_photo_behind_url,true);
        $criteria->compare('extra_params',$this->extra_params,true);
        $criteria->compare('type',$this->type);
        $criteria->compare('short_link_id',$this->short_link_id,true);
        $criteria->compare('utm_source',$this->utm_source,true);
        $criteria->compare('utm_medium',$this->utm_medium,true);
        $criteria->compare('utm_campaign',$this->utm_campaign,true);
        $criteria->compare('utm_content',$this->utm_content,true);
        $criteria->compare('sale_offices_id',$this->sale_offices_id,true);
        $criteria->compare('vnp_province_id',$this->vnp_province_id,true);
        $criteria->compare('commissions_type',$this->commissions_type,true);
        $criteria->compare('yearmonth',$this->yearmonth);
        $criteria->compare('is_scan_total',$this->is_scan_total);
        $criteria->compare('process_traffix',$this->process_traffix);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * @return CDbConnection the database connection used for this class
     */
    public function getDbConnection()
    {
        return Yii::app()->db_freedoo_tourist;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FTActions the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
