<?php

/**
 * This is the model class for table "{{package}}".
 *
 * The followings are the available columns in table '{{package}}':
 * @property string $id
 * @property string $name
 * @property string $code
 * @property string $code_vnpt
 * @property string $short_description
 * @property string $description
 * @property string $price
 * @property integer $type
 * @property string $extra_params
 * @property integer $status
 * @property string $thumbnail_1
 * @property string $thumbnail_2
 * @property string $thumbnail_3
 * @property string $point
 * @property integer $sort_index
 * @property integer $category_id
 * @property integer $period
 * @property string $commission_rate_publisher
 * @property string $commission_rate_agency
 * @property integer $home_display
 * @property integer $vip_user
 * @property string $price_discount
 * @property integer $sms_external
 * @property integer $sms_internal
 * @property integer $call_external
 * @property integer $call_internal
 * @property double $data
 * @property string $range_age
 * @property integer $hot
 * @property integer $stock_id
 * @property string $highlight
 * @property integer $freedoo
 * @property string $cp_id
 * @property integer $display_type
 * @property string $slug
 * @property integer $display_in_checkout
 * @property integer $delivery_location_in_checkout
 * @property integer $package_local
 * @property string $parent_code
 */
class Package extends CActiveRecord
{
    public $type_tv;
    public $commercial;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{package}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('type, status, sort_index, category_id, period, home_display, vip_user, sms_external, sms_internal, call_external, call_internal, hot, stock_id, freedoo, display_type, display_in_checkout, delivery_location_in_checkout, package_local', 'numerical', 'integerOnly'=>true),
			array('data', 'numerical'),
			array('id', 'length', 'max'=>100),
			array('name, code, code_vnpt, short_description, thumbnail_1, thumbnail_2, thumbnail_3, range_age, highlight, cp_id, slug, parent_code', 'length', 'max'=>255),
			array('price, commission_rate_publisher, commission_rate_agency, price_discount', 'length', 'max'=>10),
			array('extra_params', 'length', 'max'=>500),
			array('point', 'length', 'max'=>11),
			array('description,type_tv,commercial,price_stb,price_no_stb', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, code, code_vnpt, short_description, description, price, type, extra_params, status, thumbnail_1, thumbnail_2, thumbnail_3, point, sort_index, category_id, period, commission_rate_publisher, commission_rate_agency, home_display, vip_user, price_discount, sms_external, sms_internal, call_external, call_internal, data, range_age, hot, stock_id, highlight, freedoo, cp_id, display_type, slug, display_in_checkout, delivery_location_in_checkout, package_local, parent_code, type_tv, commercial,price_stb,price_no_stb', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'code' => 'Code',
			'code_vnpt' => 'Code Vnpt',
			'short_description' => 'Short Description',
			'description' => 'Description',
			'price' => 'Price',
			'type' => 'Type',
			'extra_params' => 'Extra Params',
			'status' => 'Status',
			'thumbnail_1' => 'Thumbnail 1',
			'thumbnail_2' => 'Thumbnail 2',
			'thumbnail_3' => 'Thumbnail 3',
			'point' => 'Point',
			'sort_index' => 'Sort Index',
			'category_id' => 'Category',
			'period' => 'Period',
			'commission_rate_publisher' => 'Commission Rate Publisher',
			'commission_rate_agency' => 'Commission Rate Agency',
			'home_display' => 'Home Display',
			'vip_user' => 'Vip User',
			'price_discount' => 'Price Discount',
			'sms_external' => 'Sms External',
			'sms_internal' => 'Sms Internal',
			'call_external' => 'Call External',
			'call_internal' => 'Call Internal',
			'data' => 'Data',
			'range_age' => 'Range Age',
			'hot' => 'Hot',
			'stock_id' => 'Stock',
			'highlight' => 'Highlight',
			'freedoo' => 'Freedoo',
			'cp_id' => 'Cp',
			'display_type' => 'Display Type',
			'slug' => 'Slug',
			'display_in_checkout' => 'Display In Checkout',
			'delivery_location_in_checkout' => 'Delivery Location In Checkout',
			'package_local' => 'Package Local',
			'parent_code' => 'Parent Code',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('code_vnpt',$this->code_vnpt,true);
		$criteria->compare('short_description',$this->short_description,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('extra_params',$this->extra_params,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('thumbnail_1',$this->thumbnail_1,true);
		$criteria->compare('thumbnail_2',$this->thumbnail_2,true);
		$criteria->compare('thumbnail_3',$this->thumbnail_3,true);
		$criteria->compare('point',$this->point,true);
		$criteria->compare('sort_index',$this->sort_index);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('period',$this->period);
		$criteria->compare('commission_rate_publisher',$this->commission_rate_publisher,true);
		$criteria->compare('commission_rate_agency',$this->commission_rate_agency,true);
		$criteria->compare('home_display',$this->home_display);
		$criteria->compare('vip_user',$this->vip_user);
		$criteria->compare('price_discount',$this->price_discount,true);
		$criteria->compare('sms_external',$this->sms_external);
		$criteria->compare('sms_internal',$this->sms_internal);
		$criteria->compare('call_external',$this->call_external);
		$criteria->compare('call_internal',$this->call_internal);
		$criteria->compare('data',$this->data);
		$criteria->compare('range_age',$this->range_age,true);
		$criteria->compare('hot',$this->hot);
		$criteria->compare('stock_id',$this->stock_id);
		$criteria->compare('highlight',$this->highlight,true);
		$criteria->compare('freedoo',$this->freedoo);
		$criteria->compare('cp_id',$this->cp_id,true);
		$criteria->compare('display_type',$this->display_type);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('display_in_checkout',$this->display_in_checkout);
		$criteria->compare('delivery_location_in_checkout',$this->delivery_location_in_checkout);
		$criteria->compare('package_local',$this->package_local);
		$criteria->compare('parent_code',$this->parent_code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Package the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
