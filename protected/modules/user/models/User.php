<?php

/**
 * The followings are the available columns in table 'users':
 *
 * @property  integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $activkey
 * @property integer $createtime
 * @property integer $lastvisit
 * @property integer $parent_id
 * @property integer $superuser
 * @property integer $status
 * @property integer $token_key
 * @property integer $phone
 * @property integer $phone_2
 * @property integer $unit_id
 * @property integer $province_code
 * @property integer $district_code
 * @property integer $ward_code
 * @property integer $sale_offices_id
 * @property integer $brand_offices_id
 * @property integer $agency_id
 */
class User extends CActiveRecord
{
    const STATUS_NOACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BANED = -1;

    public $leaderShift;
    public $groupRole;
    public $unit_id;
    public $re_password;

    public $regency;

    public $fullname;

    /**
     * Returns the static model of the specified AR class.
     *
     * @return User the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return Yii::app()->getModule('user')->tableUsers;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.

        return array(
            array('username, password, email,phone', 'required'),
            array('username', 'length', 'max' => 20, 'min' => 3, 'message' => UserModule::t("Incorrect username (length between 3 and 20 characters).")),
            array('password', 'length', 'max' => 128, 'min' => 4, 'message' => UserModule::t("Incorrect password (minimal length 4 symbols).")),
            array('username', 'match', 'pattern' => '/^([A-Za-z0-9_\.-])+$/', 'message' => "Chỉ chấp nhận các ký tự từ a->z, 0->9 và ký tự _ , ký tự - và dấu chấm"),
            array('re_password', 'length', 'max' => 128, 'min' => 4, 'message' => UserModule::t("Incorrect password (minimal length 4 symbols).")),
            array('re_password', 'compare', 'compareAttribute' => 'password', 'on' => 'create'),
            array('username', 'unique', 'message' => UserModule::t("This user's name already exists.")),
            array('email', 'unique', 'message' => UserModule::t("This user's email address already exists.")),
//                array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u', 'message' => UserModule::t("Incorrect symbols (A-z0-9).")),
            array('status', 'in', 'range' => array(self::STATUS_NOACTIVE, self::STATUS_ACTIVE, self::STATUS_BANED)),
            array('superuser', 'in', 'range' => array(0, 1)),
            array('phone', 'authenticateMsisdn'),
            array('phone_2', 'authenticateMsisdn'),
//                array('phone', 'unique', 'className' => 'User', 'attributeName' => 'phone', 'message' => 'Số điện thoại này đã được đăng ký!'),
            array('createtime, lastvisit, superuser, status', 'required'),
            array('createtime, lastvisit, superuser, status, parent_id', 'numerical', 'integerOnly' => TRUE),
            array('province_code, district_code, ward_code, brand_offices_id, sale_offices_id,regency', 'length', 'max' => 255),
            array('unit_id, agency_id', 'safe'),
        );

    }

    public function search($dataProvider = TRUE)
    {
        $criteria = new CDbCriteria;

        $model = new User();
        if (ADMIN || ADMIN_CSKH) {
            $criteria->condition = "t.username !='admin'";
        }
        if (!SUPER_ADMIN && !ADMIN && !ADMIN_CSKH) {

            if (Yii::app()->user->province_code) {
                if (!isset(Yii::app()->user->sale_offices_id) || Yii::app()->user->sale_offices_id == '') {
                    $criteria->compare('t.province_code', Yii::app()->user->province_code);
                } else {
                    if (Yii::app()->user->sale_offices_id != '') {
                        if (isset(Yii::app()->user->brand_offices_id) && Yii::app()->user->brand_offices_id != '') {
                            $criteria->compare('t.brand_offices_id', Yii::app()->user->brand_offices_id);
                        }
                        $criteria->compare('sale_offices_id', Yii::app()->user->sale_offices_id);
                    }
                }
            } else {
                if (Yii::app()->user->checkAccess("LeaderShift")) {
                    if (isset(Yii::app()->user->unit_id)) {
                        $criteria->addCondition("t.unit_id ='" . Yii::app()->user->unit_id . "'");
                    } else {
                        $criteria->compare('t.parent_id', Yii::app()->user->id);
                    }
                } else {
                    $criteria->compare('t.parent_id', Yii::app()->user->id);
                }
            }
        }

        if (ADMIN_CSKH) {
            $criteria->addCondition("a.itemname IN('Admin_cskh','KTV','LeaderShift')");
        }

        if (isset($_REQUEST['User']['username']) && $_REQUEST['User']['username'] != '') {
            $criteria->addCondition("t.username like '" . $_REQUEST['User']['username'] . "%'");
            $model->username = $_REQUEST['User']['username'];
        }
        if (isset($_REQUEST['User']['phone']) && $_REQUEST['User']['phone'] != '') {
            $criteria->addCondition("t.phone ='" . $_REQUEST['User']['phone'] . "'");
            $model->phone = $_REQUEST['User']['phone'];
        }
        if (isset($_REQUEST['User']['province_code'])) {
            if ($_REQUEST['User']['province_code'] != '') {
                $criteria->addCondition("t.province_code = '" . $_REQUEST['User']['province_code'] . "'");
                $model->province_code = $_REQUEST['User']['province_code'];
            }
        }
        if (isset($_REQUEST['User']['sale_offices_id'])) {
            if ($_REQUEST['User']['sale_offices_id'] != '') {
                $criteria->addCondition("t.sale_offices_id = '" . $_REQUEST['User']['sale_offices_id'] . "'");
                $model->sale_offices_id = $_REQUEST['User']['sale_offices_id'];
            }
        }
        if (isset($_REQUEST['User']['brand_offices_id'])) {
            if ($_REQUEST['User']['brand_offices_id'] != '') {
                $criteria->addCondition("t.brand_offices_id = '" . $_REQUEST['User']['brand_offices_id'] . "'");
                $model->brand_offices_id = $_REQUEST['User']['brand_offices_id'];
            }
        }
        if (isset($_REQUEST['User']['status'])) {
            if ($_REQUEST['User']['status'] != '') {
                $criteria->addCondition("t.status = '" . $_REQUEST['User']['status'] . "'");
                $model->status = $_REQUEST['User']['status'];
            }
        }
        if (ADMIN_CSKH) {
            $criteria->join = "INNER JOIN tbl_authassignment a ON a.userid = t.id";
        }

        if (isset(Yii::app()->user->agency) && !empty(Yii::app()->user->agency)){
            $criteria->compare('t.agency_id', Yii::app()->user->agency, FALSE);
        }

        if($dataProvider){
            return new CActiveDataProvider('User', array(
                'criteria'   => $criteria,
                'pagination' => array(
                    'params'   => array(
                        'User[brand_offices_id]' => $model->brand_offices_id,
                        'User[sale_offices_id]'  => $model->sale_offices_id,
                        'User[province_code]'    => $model->province_code,
                        'User[username]'         => $model->username,
                        'User[phone]'            => $model->phone,
                    ),
                    'pageSize' => 50,
                ),
            ));
        }else{
            return User::model()->findAll($criteria);
        }
    }


    public function afterFind()
    {
        $accept_controllers = array(
            'admin',
            'login',
            'loginCskh',
            'loginAgency',
            'excelExport'
        );
        $controller_id = Yii::app()->controller->id;

        if(in_array($controller_id, $accept_controllers)){
            if ($this->id && $this->parent_id) {
                $criteria = new CDbCriteria();
                $criteria->condition = "user_id IN('" . $this->id . "','" . $this->parent_id . "')";
                $unit_user = UnitUser::model()->find($criteria);
                if ($unit_user) {
                    $this->unit_id = $unit_user->unit_id;
                }

                if($controller_id == 'admin' || ($controller_id == 'excelExport' && Yii::app()->controller->action->id  == 'userAdmin')){
                    if (!$this->groupRole || !$this->regency) {
                        $criteria->select = "
                            t.*, 
                            (SELECT scope FROM tbl_roles WHERE name = t.itemname LIMIT 1) as 'scope',
                            (SELECT id FROM tbl_roles WHERE name = t.itemname LIMIT 1) as 'role_id'
                        ";
                        $criteria->condition = "t.userid = '$this->id' AND itemname NOT LIKE '%.%'";
                        $auth = Authassignment::model()->find($criteria);
                        if ($auth) {
                            $role = Roles::model()->getRoleName($auth->role_id);
                            $this->groupRole = $this->regency = $role;
                        }
                    }
                }
            }
        }
        return TRUE;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        $relations = array(
            'profile' => array(self::HAS_ONE, 'Profile', 'user_id'),
        );
        if (isset(Yii::app()->getModule('user')->relations)) $relations = array_merge($relations, Yii::app()->getModule('user')->relations);

        return $relations;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'username' => UserModule::t("username"),
            'password' => UserModule::t("password"),
            'verifyPassword' => UserModule::t("Retype Password"),
            'email' => UserModule::t("E-mail"),
            'verifyCode' => UserModule::t("Verification Code"),
            'id' => UserModule::t("Id"),
            'activkey' => UserModule::t("activation key"),
            'createtime' => UserModule::t("Registration date"),
            'lastvisit' => UserModule::t("Last visit"),
            'superuser' => "Admin",
            'status' => "Trạng thái",
            'leaderShift' => 'Đơn vị',
            'parent_id' => 'Đơn vị trực thuộc',
            'groupRole' => 'Nhóm quyền',
            'province_code' => 'Chọn trung tâm kinh doanh',
            'district_code' => 'Quận huyện',
            'ward_code' => 'Phường xã',
            'brand_offices_id' => 'Điểm giao dịch',
            're_password' => 'Xác nhận mật khẩu',
            'regency' => 'Chức vụ',
            'phone' => 'Số điện thoại',
            'phone_2' => 'Số điện thoại 2',
            'unit_id' => 'Chọn đơn vị',
            'sale_offices_id' => 'Chọn phòng bán hàng',
            'fullname' => 'Họ và tên',
            'agency_id' => 'Đại lý',
        );
    }

    public function scopes()
    {
        return array(
            'active' => array(
                'condition' => 'status=' . self::STATUS_ACTIVE,
            ),
            'notactvie' => array(
                'condition' => 'status=' . self::STATUS_NOACTIVE,
            ),
            'banned' => array(
                'condition' => 'status=' . self::STATUS_BANED,
            ),
            'superuser' => array(
                'condition' => 'superuser=1',
            ),
            'notsafe' => array(
                'select' => 'id, username, password, email, activkey, createtime, lastvisit, superuser, status',
            ),
        );
    }

//        public function defaultScope()
//        {
//            return array(
//                'select' => 'id, username, email, createtime, lastvisit, superuser, status',
//            );
//        }


    public static function itemAlias($type, $code = NULL)
    {
        $_items = array(
            'UserStatus' => array(
                self::STATUS_NOACTIVE => UserModule::t('Not active'),
                self::STATUS_ACTIVE => UserModule::t('Active'),
                self::STATUS_BANED => UserModule::t('Banned'),
            ),
            'AdminStatus' => array(
                '0' => UserModule::t('No'),
                '1' => UserModule::t('Yes'),
            ),
        );
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : FALSE;
        else
            return isset($_items[$type]) ? $_items[$type] : FALSE;
    }

    /**
     * Lấy nhóm quyền
     *
     * @return array
     */
    public function getGroupRow()
    {
        if (!SUPER_ADMIN && !ADMIN && !ADMIN_CSKH) {
            return array(
                'KTV' => 'KTV',
            );
        }
        $criteria = new CDbCriteria();
        $criteria->condition = "t.scope = 'cskh' AND t.parent_id IS NULL OR t.parent_id = ''";
        $models = Roles::model()->findAll($criteria);

        return CHtml::listData($models,'name','description');
    }

    /**
     * Lấy nhóm quyền
     *
     * @return array
     */
    public function getRegency()
    {
        $criteria = new CDbCriteria();

        if(isset(Yii::app()->user->agency) || !empty(Yii::app()->user->agency)){
            $scope = 'agency';
        }else{
            $scope = 'adm';
        }

        $criteria->condition = "t.scope = '$scope' AND t.parent_id IS NULL OR t.parent_id = ''";
        $models = Roles::model()->findAll($criteria);

        return CHtml::listData($models,'name','description');

    }

    public function authenticateMsisdn($attribute, $params)
    {
        if ($this->$attribute) {
            $short_pattern = "/^0[0-9]{9,10}$/i";
            $full_pattern = "/^84[0-9]{9,10}$/i";
            $input = $this->$attribute;
            if (preg_match($short_pattern, $input) == TRUE || preg_match($full_pattern, $input) == TRUE) {
                return TRUE;
            } else {
                $this->addError($attribute, 'Số điện thoại không đúng định dạng!');
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * Nhận người dùng cskh
     */
    public function getLeaderUser()
    {
        $criteria = new CDbCriteria;
        $criteria->select = '*,u.id as user_id, u.username as username';
        if (SUPER_ADMIN || ADMIN) {
            $criteria->condition = 't.itemname in ("LeaderShift","Admin")';
        }
        $criteria->join = 'INNER JOIN {{users}} u on u.id = t.userid';
        $user_cskh = Authassignment::model()->findAll($criteria);

        return CHtml::listData($user_cskh, 'user_id', 'username');
    }

    /**
     * Nhận người dùng cskh
     */
    public function getCskhUser()
    {
        $criteria = new CDbCriteria;
        $criteria->select = '*,u.id as user_id, u.username as username';
        if (SUPER_ADMIN || ADMIN) {
            $criteria->condition = 't.itemname in ("LeaderShift","Admin","KTV")';
        }
        $criteria->join = 'INNER JOIN {{users}} u on u.id = t.userid';
        $user_cskh = Authassignment::model()->findAll($criteria);

        return CHtml::listData($user_cskh, 'user_id', 'username');
    }

    /**
     * Lấy tất cả đơn vị
     */
    public function getAllUnit()
    {
        $criteria = new CDbCriteria();
        if (isset(Yii::app()->user->unit_id)) {
            if (!empty(Yii::app()->user->unit_id)) {
                $criteria->condition = "id='" . Yii::app()->user->unit_id . "'";
            }
        }
        $unit = Unit::model()->findAll($criteria);

        return CHtml::listData($unit, 'id', 'name');
    }


    public function getFullName($id)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = "user_id ='" . $id . "'";
        $profile = Profile::model()->find($criteria);

        return (isset($profile->lastname) && isset($profile->firstname)) ? $profile->firstname . " " . $profile->lastname : '';
    }

    /**
     * Lấy user phòng bán hàng dn theo mã trung tâm kinh doanh.
     * @param $vnp_province_id string
     * @return array
     */
    public
    static function getUserPBHDN($vnp_province_id)
    {
        $data = array();
        if ($vnp_province_id) {
            $province = Province::model()->findByAttributes(array('vnp_province_id' => $vnp_province_id));
            if ($province) {
                $criteria = new CDbCriteria();
                $criteria->select = "t.*";
                $criteria->condition = "t.province_code ='$province->code' and a.itemname='PBH_DN'";
                $criteria->join = "INNER JOIN {{authassignment}} a ON a.userid = t.id";
                $data = CHtml::listData(User::model()->findAll($criteria), 'id', 'username');
            }
        }

        return $data;
    }

    protected function beforeSave()
    {
        if(isset(Yii::app()->user->agency)){
            $this->agency_id = Yii::app()->user->agency;
        }
        if(empty($this->province_code)){
            $this->sale_offices_id = '';
            $this->brand_offices_id = '';
        }else if(empty($this->sale_offices_id)){
            $this->brand_offices_id = '';
        }
        return TRUE;
    }


    /**
     * Gắn quyền
     */
    public function afterSave()
    {
        //Nếu là CSKH
        if (isset($_POST['User']['groupRole']) && !empty($_POST['User']['groupRole'])) {
            $role = $_POST['User']['groupRole'];

            $criteria = new CDbCriteria();
            $criteria->condition = "userid = '$this->id'";
            $auth = Authassignment::model()->find($criteria);

            if (!$auth) {
                $auth = new Authassignment();
            }
            $auth->itemname = $role;
            $auth->userid = $this->id;
            $auth->save();
        }
        //Nếu là adm
        if (isset($_POST['User']['regency']) && !empty($_POST['User']['regency'])) {
            $regency = $_POST['User']['regency'];
            $itemname = '';

            if ($regency == 'ADMIN') {

                if ($this->brand_offices_id != '' && $this->sale_offices_id != '') {
                    $itemname = 'DGD';
                } else if ($this->sale_offices_id != '' && $this->brand_offices_id == '') {
                    $itemname = 'PBH';
                } else if ($this->brand_offices_id == '' && $this->sale_offices_id == '' && $this->province_code != '') {
                    $itemname = 'TTKD';
                }
            } else if ($regency == 'STAFF') {
                if ($this->brand_offices_id != '' && $this->sale_offices_id != '') {
                    $itemname = 'DGD_SUB';
                } else if ($this->sale_offices_id != '' && $this->brand_offices_id == '') {
                    $itemname = 'PBH_SUB';
                } else if ($this->brand_offices_id == '' && $this->sale_offices_id == '' && $this->province_code != '') {
                    $itemname = 'TTKD_SUB';
                }
            } else if ($regency == 'PBH_DN') {

                if ($this->province_code != '') {
                    $this->brand_offices_id = $this->sale_offices_id = '';
                    $itemname = $regency;
                }
            } else {
                if ($this->province_code != '') {
                    $itemname = $regency;
                }
            }

            if (!empty($this->agency_id)) {
                if($regency == 'ADMIN' || $regency == 'STAFF'){
                    if(!empty($itemname)){
                        $itemname.= '_DTBL';
                    }else{
                        $itemname = 'ADMIN_DTBL';
                    }
                }
            }

            if (!empty($itemname)) {
                $criteria = new CDbCriteria();
                $criteria->condition = "t.userid = '$this->id' AND itemname NOT LIKE '%.%'";
                $auth = Authassignment::model()->find($criteria);

                if (!$auth) {
                    $auth = new Authassignment();
                }
                $auth->userid = $this->id;
                $auth->itemname = $itemname;
                $auth->save();
            }
        }



    }

    /**
     * Lấy danh sách user đại diện theo location
     * @param $type 1: PBH | 2: DGD
     * @return CActiveDataProvider
     */
    public function search_proxy($type)
    {
        $criteria = new CDbCriteria();

        if ($this->sale_offices_id != '') {
            $criteria->compare('t.sale_offices_id', $this->sale_offices_id, FALSE);
        }
        if ($this->brand_offices_id != '') {
            $criteria->compare('t.brand_offices_id', $this->brand_offices_id, FALSE);
        }
        if ($type == 1) {
            $criteria->addCondition("a.itemname='PBH'");
        } else {
            $criteria->addCondition("a.itemname='DGD'");
        }
        $criteria->join = "INNER JOIN {{authassignment}} a ON a.userid = t.id";

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 't.createtime DESC',
            ),
            'pagination' => array(
                'pageSize' => 30,
            ),
        ));
    }

    /**
     * @param $user_id
     *
     * @return string
     */
    public static function getUserName($user_id)
    {
        $model = '';
        if ($user_id) {
            $model = self::model()->find('id=:id', array(':id' => $user_id));
        }

        return ($model) ? CHtml::encode($model->username) : $user_id;
    }

    public static function getListAgency(){
        $arr_agency = array(
            '' => 'freedoo',
        );

        $list_agency = Agency::model()->findAll();
        if(!empty($list_agency)){
            foreach ($list_agency as $agency){
                $arr_agency[$agency->code] = $agency->name;
            }
        }

        return $arr_agency;
    }
}
