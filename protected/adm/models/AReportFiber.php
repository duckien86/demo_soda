<?php

class AReportFiber extends RegFiber{
    public $start_date;
    public $end_date;
    public $created_on;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{reg_fiber}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tinh_id, hdkh_id, khachhang_id, thuebao_id, quan_id, phuong_id, pho_id, khu_id, ap_id, dacdiem_id, loai, loaitb_id', 'numerical', 'integerOnly'=>true),
            array('ten_kh, diachi, so_dt, noi_cap, ten_yc, so_dt_yc, ma_nd, promo_code', 'length', 'max'=>255),
            array('loai,loaitb_id', 'required','message'=>'Vui lòng không được để trống'),
            array('freedoo_order_id, fiber_order_id, so_nha, so_gt', 'length', 'max'=>50),
            array('so_dt,so_gt', 'numerical', 'message'=>'Không đúng định dạng'),
            array('ten_kh', 'required','message'=>'Tên khách hàng không được để trống'),
            array('so_dt', 'required','message'=>'Số điện thoại khách hàng không được để trống'),
            array('tinh_id', 'required','message'=>'Tỉnh/TP không được để trống'),
            array('quan_id', 'required','message'=>'Quận/Huyện không được để trống'),
            array('phuong_id', 'required','message'=>'Xã/Phường không được để trống'),
            array('pho_id', 'required','message'=>'Phố/Khu không được để trống'),
            array('so_nha', 'required','message'=>'Số nhà không được để trống'),
            array('ngay_yc, ngay_cap, mota, ghichu,created_on', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, freedoo_order_id, fiber_order_id, hdkh_id, khachhang_id, thuebao_id, ngay_yc, ten_kh, diachi, so_dt, quan_id, phuong_id, tinh_id, pho_id, khu_id, ap_id, dacdiem_id, so_nha, so_gt, ngay_cap, noi_cap, mota, ten_yc, loai, dichvu_id, loaitb_id, ghichu, so_dt_yc, ma_nd, promo_code,created_on', 'safe', 'on'=>'search'),
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
            'id' => 'ID',
            'freedoo_order_id' => 'Freedoo Order',
            'fiber_order_id' => 'Fiber Order',
            'hdkh_id' => 'Hdkh',
            'khachhang_id' => 'Khachhang',
            'thuebao_id' => 'Thuebao',
            'ngay_yc' => 'Ngay Yc',
            'ten_kh' => 'Ten Kh',
            'diachi' => 'Diachi',
            'so_dt' => 'So Dt',
            'quan_id' => 'Quan',
            'phuong_id' => 'Phuong',
            'tinh_id' => 'Tinh',
            'pho_id' => 'Pho',
            'khu_id' => 'Khu',
            'ap_id' => 'Ap',
            'dacdiem_id' => 'Dacdiem',
            'so_nha' => 'So Nha',
            'so_gt' => 'So Gt',
            'ngay_cap' => 'Ngay Cap',
            'noi_cap' => 'Noi Cap',
            'mota' => 'Mô tả giấy tờ khách hàng',
            'ten_yc' => 'Ten Yc',
            'loai' => '1: NVKT tiếp nhận . 2: NVTC tiếp nhận',
            'dichvu_id' => 'Dichvu',
            'loaitb_id' => 'Loaitb',
            'ghichu' => 'Ghichu',
            'so_dt_yc' => 'So Dt Yc',
            'ma_nd' => 'Ma Nd',
            'promo_code' => 'Promo Code',
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

        $criteria->compare('id',$this->id);
        $criteria->compare('tinh_id',$this->tinh_id);
        $criteria->compare('hdkh_id',$this->hdkh_id);
        $criteria->compare('khachhang_id',$this->khachhang_id);
        $criteria->compare('thuebao_id',$this->thuebao_id);
        $criteria->compare('ngay_yc',$this->ngay_yc,true);
        $criteria->compare('ten_kh',$this->ten_kh,true);
        $criteria->compare('diachi',$this->diachi,true);
        $criteria->compare('so_dt',$this->so_dt,true);
        $criteria->compare('quan_id',$this->quan_id);
        $criteria->compare('phuong_id',$this->phuong_id);
        $criteria->compare('pho_id',$this->pho_id);
        $criteria->compare('khu_id',$this->khu_id);
        $criteria->compare('ap_id',$this->ap_id);
        $criteria->compare('dacdiem_id',$this->dacdiem_id);
        $criteria->compare('so_nha',$this->so_nha,true);
        $criteria->compare('so_gt',$this->so_gt,true);
        $criteria->compare('ngay_cap',$this->ngay_cap,true);
        $criteria->compare('noi_cap',$this->noi_cap,true);
        $criteria->compare('mota',$this->mota,true);
        $criteria->compare('ma_nd',$this->ma_nd,true);
        $criteria->compare('loai',$this->loai);
        $criteria->compare('dichvu_id',$this->dichvu_id);
        $criteria->compare('loaitb_id',$this->loaitb_id);
        $criteria->compare('ghichu',$this->ghichu,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    /*
     * Lấy danh sách fiber
     */
    public function getFiberOrder(){
        if($this->start_date && $this->end_date){
            $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
            $this->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
        }
        $criteria  = new CDbCriteria();
        $criteria->select = "*";
        $criteria->addCondition("created_on >= '$this->start_date' AND created_on <= '$this->end_date' ");
        if($this->freedoo_order_id){
            $criteria->addCondition("freedoo_order_id = '$this->freedoo_order_id' ");
        }
        $data = self::model()->findAll($criteria);
        return $data;
    }
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RegFiber the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}