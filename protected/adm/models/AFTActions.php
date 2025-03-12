<?php

class AFTActions extends FTActions
{
    public $input_type;
    public $info_search;
    public $status_change;

    public $total_commision;
    public $total_renueve;
    public $total_order;

    public $price_sim;
    public $price_package;
    public $agency_id;

    public $bundle;

    public $active_time;
    public $active_date;

    CONST CAMPAIGN_CATEGORY_ID_SIM = 3;     //sim
    CONST CAMPAIGN_CATEGORY_ID_PACKAGE = 4; //gói
    CONST CAMPAIGN_CATEGORY_ID_CONSUME = 5; //tiêu dùng TKC

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('start_date, end_date', 'required'),
            array(
                'end_date',
                'compare',
                'compareAttribute' => 'start_date',
                'operator'         => '>=',
                'allowEmpty'       => FALSE,
                'message'          => "Ngày kết thúc phải lớn hơn ngày bắt đầu"
            ),
            array('info_search, start_date, end_date', 'required'),
//                array('action_id, transaction_id, publisher_id, amount, action_status, created_on, order_code, product_name', 'required'),
            array('campaign_id, campaign_category_id, action_status', 'numerical', 'integerOnly' => TRUE),
            array('merchant_id, amount, type, price, price_discount, total_money', 'numerical'),
            array('action_id, transaction_id, publisher_id, click_id', 'length', 'max' => 128),
            array('inviter_code', 'length', 'max' => 255),
            array('order_code, product_name', 'length', 'max' => 1024),
            array('client_name, client_email, client_address, product_id, personal_photo_font_url, personal_photo_behind_url', 'length', 'max' => 512),
            array('client_mobile', 'length', 'max' => 20),
            array('quantity, sale_offices_id, yearmonth', 'length', 'max' => 11),
            array('personal_id', 'length', 'max' => 60),
            array('order_time, note, extra_params, package_code, msisdn, 
                    bundle, active_time,active_date', 'safe'),
            array('short_link_id', 'length', 'max' => 10),
            array('utm_source', 'length', 'max' => 255),
            array('package_code, vnp_province_id', 'length', 'max' => 255),
            array('msisdn', 'length', 'max' => 20),
            array('utm_medium, utm_campaign, utm_content', 'length', 'max' => 512),
            array('commissions_type', 'length', 'max' => 50),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('action_id, transaction_id, merchant_id, publisher_id, campaign_id, click_id,inviter_code,  campaign_category_id, amount,type, action_status, created_on, order_code, client_name, client_email, client_address, client_mobile, order_time, note, quantity, product_name, package_code,msisdn,  product_id, price, price_discount, total_money, personal_id, personal_photo_font_url, personal_photo_behind_url, extra_params,sale_offices_id, vnp_province_id, commissions_type, yearmonth,active_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'action_id'                 => 'action_id',
            'transaction_id'            => 'transaction_id',
            'merchant_id'               => 'merchant_id',
            'publisher_id'              => 'ID CTV/ĐLTC',
            'campaign_id'               => 'Campaign ID',
            'click_id'                  => 'Click ID',
            'inviter_code'              => 'Mã giới thiệu',
            'campaign_category_id'      => 'Danh mục',
            'amount'                    => 'Hoa hồng',
            'action_status'             => 'Trạng thái',
            'created_on'                => 'Ngày tạo',
            'order_code'                => 'Mã ĐH',
            'client_name'               => 'Tên khách hàng',
            'client_email'              => 'Email',
            'client_address'            => 'Địa chỉ',
            'client_mobile'             => 'Điện thoại',
            'order_time'                => 'Ngày mua',
            'note'                      => 'Ghi chú',
            'quantity'                  => 'Số lượng',
            'product_name'              => 'Tên sản phẩm',
            'package_code'              => 'Mã gói',
            'msisdn'                    => 'Sô thuê bao',
            'product_id'                => 'Product ID',
            'price'                     => 'Giá gốc',
            'price_discount'            => 'Giá mua',
            'total_money'               => 'Tổng tiền',
            'personal_id'               => 'Số CMT/căn cước',
            'personal_photo_font_url'   => 'Ảnh mặt trước CMT',
            'personal_photo_behind_url' => 'Ảnh mặt sau CMT',
            'extra_params'              => 'Extra Params',
            'type'                      => 'Loại thuê bao',
            'bundle'                    => 'Loại gói',

            'short_link_id'    => 'Link rút gọn',
            'utm_source'       => 'utm_source',
            'utm_medium'       => 'utm_medium',
            'utm_campaign'     => 'utm_campaign',
            'utm_content'      => 'utm_content',
            'sale_offices_id'  => 'ID phòng bán hàng',
            'vnp_province_id'  => 'Mã trung tâm kinh doanh',
            'commissions_type' => 'Kiểu hoa hồng',
            'yearmonth'        => 'Hoa hồng gia hạn tháng',
            'start_date'       => 'Ngày bắt đầu',
            'end_date'         => 'Ngày kêt thúc',
            'input_type'       => 'Chọn tiêu chí tra cứu',
            'info_search'      => 'Thông tin tra cứu',
            'active_time'      => 'Ngày mở gói',
            'active_date'      => 'Ngày kích hoạt',
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

        $criteria = new CDbCriteria;

        $criteria->compare('action_id', $this->action_id, TRUE);
        $criteria->compare('transaction_id', $this->transaction_id, TRUE);
        $criteria->compare('merchant_id', $this->merchant_id, TRUE);
        $criteria->compare('publisher_id', $this->publisher_id, TRUE);
        $criteria->compare('campaign_id', $this->campaign_id);
        $criteria->compare('click_id', $this->click_id, TRUE);
        $criteria->compare('inviter_code', $this->inviter_code, TRUE);
        $criteria->compare('campaign_category_id', $this->campaign_category_id);
        $criteria->compare('amount', $this->amount);
        $criteria->compare('type', $this->type);
        $criteria->compare('action_status', $this->action_status);
        $criteria->compare('created_on', $this->created_on, TRUE);
        $criteria->compare('order_code', $this->order_code, TRUE);
        $criteria->compare('client_name', $this->client_name, TRUE);
        $criteria->compare('client_email', $this->client_email, TRUE);
        $criteria->compare('client_address', $this->client_address, TRUE);
        $criteria->compare('client_mobile', $this->client_mobile, TRUE);
        $criteria->compare('order_time', $this->order_time, TRUE);
        $criteria->compare('note', $this->note, TRUE);
        $criteria->compare('quantity', $this->quantity, TRUE);
        $criteria->compare('product_name', $this->product_name, TRUE);
        $criteria->compare('package_code', $this->package_code, TRUE);
        $criteria->compare('msisdn', $this->msisdn, TRUE);
        $criteria->compare('product_id', $this->product_id, TRUE);
        $criteria->compare('price', $this->price);
        $criteria->compare('price_discount', $this->price_discount);
        $criteria->compare('total_money', $this->total_money);
        $criteria->compare('personal_id', $this->personal_id, TRUE);
        $criteria->compare('personal_photo_font_url', $this->personal_photo_font_url, TRUE);
        $criteria->compare('personal_photo_behind_url', $this->personal_photo_behind_url, TRUE);
        $criteria->compare('extra_params', $this->extra_params, TRUE);

        $criteria->compare('short_link_id', $this->short_link_id, TRUE);
        $criteria->compare('utm_source', $this->utm_source, TRUE);
        $criteria->compare('utm_medium', $this->utm_medium, TRUE);
        $criteria->compare('utm_campaign', $this->utm_campaign, TRUE);
        $criteria->compare('utm_content', $this->utm_content, TRUE);
        $criteria->compare('sale_offices_id', $this->sale_offices_id, TRUE);
        $criteria->compare('vnp_province_id', $this->vnp_province_id, TRUE);
        $criteria->compare('commissions_type', $this->commissions_type, TRUE);
        $criteria->compare('yearmonth', $this->yearmonth, TRUE);
        $criteria->compare('active_date', $this->active_date, TRUE);

        return new CActiveDataProvider($this, array(
            'criteria'   => $criteria,
            'sort'       => array('defaultOrder' => 'created_on DESC'),
            'pagination' => array(
                'pageSize' => 20
            )
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className active record class name.
     *
     * @return AFTActions the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

}
