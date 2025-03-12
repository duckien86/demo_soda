<?php

/**
 * This is the model class for table "{{reg_fiber}}".
 *
 * The followings are the available columns in table '{{reg_fiber}}':
 * @property integer $id
 * @property string $freedoo_order_id
 * @property string $fiber_order_id
 * @property integer $hdkh_id
 * @property integer $khachhang_id
 * @property integer $thuebao_id
 * @property string $ngay_yc
 * @property string $ten_kh
 * @property string $diachi
 * @property string $so_dt
 * @property integer $quan_id
 * @property integer $phuong_id
 * @property integer $tinh_id
 * @property integer $pho_id
 * @property integer $khu_id
 * @property integer $ap_id
 * @property integer $dacdiem_id
 * @property string $so_nha
 * @property string $so_gt
 * @property string $ngay_cap
 * @property string $noi_cap
 * @property string $mota
 * @property string $ten_yc
 * @property integer $loai
 * @property integer $dichvu_id
 * @property integer $loaitb_id
 * @property string $ghichu
 * @property string $so_dt_yc
 * @property string $ma_nd
 * @property string $promo_code
 * @property string $created_on
 * @property string $goidv
 * @property string $ngay_ky_hd
 * @property string $ten_nv
 * @property string $ma_nv
 * @property string $ma_tb
 * @property string $phong_bh
 * @property string $loaihinh_tb
 * @property string $thangtratruoc
 */
class RegFiber extends CActiveRecord
{
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
			array('hdkh_id, khachhang_id, thuebao_id, quan_id, phuong_id, tinh_id, pho_id, khu_id, ap_id, dacdiem_id, loai, dichvu_id, loaitb_id', 'numerical', 'integerOnly'=>true),
			array('freedoo_order_id, fiber_order_id, so_nha, so_gt', 'length', 'max'=>50),
			array('ten_kh, diachi, so_dt, noi_cap, ten_yc, so_dt_yc, ma_nd, promo_code, goidv, ngay_ky_hd, ten_nv, ma_nv, ma_tb, phong_bh, loaihinh_tb, thangtratruoc', 'length', 'max'=>255),
			array('ngay_yc, ngay_cap, mota, ghichu, created_on', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, freedoo_order_id, fiber_order_id, hdkh_id, khachhang_id, thuebao_id, ngay_yc, ten_kh, diachi, so_dt, quan_id, phuong_id, tinh_id, pho_id, khu_id, ap_id, dacdiem_id, so_nha, so_gt, ngay_cap, noi_cap, mota, ten_yc, loai, dichvu_id, loaitb_id, ghichu, so_dt_yc, ma_nd, promo_code, created_on, goidv, ngay_ky_hd, ten_nv, ma_nv, ma_tb, phong_bh, loaihinh_tb, thangtratruoc', 'safe', 'on'=>'search'),
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
			'mota' => 'Mota',
			'ten_yc' => 'Ten Yc',
			'loai' => 'Loai',
			'dichvu_id' => 'Dichvu',
			'loaitb_id' => 'Loaitb',
			'ghichu' => 'Ghichu',
			'so_dt_yc' => 'So Dt Yc',
			'ma_nd' => 'Ma Nd',
			'promo_code' => 'Promo Code',
			'created_on' => 'Created On',
			'goidv' => 'Goidv',
			'ngay_ky_hd' => 'Ngay Ky Hd',
			'ten_nv' => 'Ten Nv',
			'ma_nv' => 'Ma Nv',
			'ma_tb' => 'Ma Tb',
			'phong_bh' => 'Phong Bh',
			'loaihinh_tb' => 'Loaihinh Tb',
			'thangtratruoc' => 'Thangtratruoc',
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
		$criteria->compare('freedoo_order_id',$this->freedoo_order_id,true);
		$criteria->compare('fiber_order_id',$this->fiber_order_id,true);
		$criteria->compare('hdkh_id',$this->hdkh_id);
		$criteria->compare('khachhang_id',$this->khachhang_id);
		$criteria->compare('thuebao_id',$this->thuebao_id);
		$criteria->compare('ngay_yc',$this->ngay_yc,true);
		$criteria->compare('ten_kh',$this->ten_kh,true);
		$criteria->compare('diachi',$this->diachi,true);
		$criteria->compare('so_dt',$this->so_dt,true);
		$criteria->compare('quan_id',$this->quan_id);
		$criteria->compare('phuong_id',$this->phuong_id);
		$criteria->compare('tinh_id',$this->tinh_id);
		$criteria->compare('pho_id',$this->pho_id);
		$criteria->compare('khu_id',$this->khu_id);
		$criteria->compare('ap_id',$this->ap_id);
		$criteria->compare('dacdiem_id',$this->dacdiem_id);
		$criteria->compare('so_nha',$this->so_nha,true);
		$criteria->compare('so_gt',$this->so_gt,true);
		$criteria->compare('ngay_cap',$this->ngay_cap,true);
		$criteria->compare('noi_cap',$this->noi_cap,true);
		$criteria->compare('mota',$this->mota,true);
		$criteria->compare('ten_yc',$this->ten_yc,true);
		$criteria->compare('loai',$this->loai);
		$criteria->compare('dichvu_id',$this->dichvu_id);
		$criteria->compare('loaitb_id',$this->loaitb_id);
		$criteria->compare('ghichu',$this->ghichu,true);
		$criteria->compare('so_dt_yc',$this->so_dt_yc,true);
		$criteria->compare('ma_nd',$this->ma_nd,true);
		$criteria->compare('promo_code',$this->promo_code,true);
		$criteria->compare('created_on',$this->created_on,true);
		$criteria->compare('goidv',$this->goidv,true);
		$criteria->compare('ngay_ky_hd',$this->ngay_ky_hd,true);
		$criteria->compare('ten_nv',$this->ten_nv,true);
		$criteria->compare('ma_nv',$this->ma_nv,true);
		$criteria->compare('ma_tb',$this->ma_tb,true);
		$criteria->compare('phong_bh',$this->phong_bh,true);
		$criteria->compare('loaihinh_tb',$this->loaihinh_tb,true);
		$criteria->compare('thangtratruoc',$this->thangtratruoc,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
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
