<?php
/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property string $language_id
 * @property string $login
 * @property string $password
 * @property string $email
 * @property string $display_name
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property integer $status
 *
 * @method User active
 * @method User cache($duration = null, $dependency = null, $queryCount = 1)
 * @method User indexed($col = 'language_id')
 * @method User limit($limit, $offset = 0)
 *
 * The followings are the available model relations:
 * @property AuthItem[] $authItems
 * @property AuthLog[] $authLogs
 */
class User extends BaseActiveRecord
{

    public $old_password;
    public $order_count;
    public $first_buy;
    public $date_first;
    public $date_last;
    public $orders_by_period;
    public $orders_date_first;
    public $orders_date_last;

    public static function fbUser($authIdentity)
    {

        /** @var $user User */
        $user = self::model()->findByAttributes(array(
            'email' => $authIdentity->getEmail()
        ));

        if(!$user)
        {
            $user = new User();
            $user->setAttributes(array(
                'password' => $user->generatePassword(),
                'email' =>  $authIdentity->getEmail(),
                'name' =>  $authIdentity->getName()
            ));
            if(!$user->save())
                return null;
        }

        if(!$user->status)
            return null;

        return $user;
    }


    /**
     * Returns User model by its email
     *
     * @param string $email
     * @access public
     * @return User
     */
    public function findByEmail($email)
    {
        return self::model()->findByAttributes(array('email' => $email));
    }

    public function afterFind()
    {
        $this->old_password=$this->password;
    }

    public function behaviors()
    {
        return array(
            'attach' => array(
                'class' => 'common.components.FileAttachBehavior',
                'imageAttributes' => array(
                    'image_id','signature_id'
                ),
                'fileAttributes' => array(
                ),
            ),
            'junction' => array(
                'class' => 'common.components.JunctionBehavior',
                'relations' => array(
                    'userUsertypes' => array(
                        'table' => '{{user_usertype}}',
                        'idColumn' => 'id',
                        'primaryColumn' => 'usertype_id',
                        'secondaryColumn' => 'user_id'
                    ),
                ),
            )
        );
    }

    /**
     * Get gender label
     *
     * @param string $gender Gender code (u, m, f)
     * @return string
     */
    public function getGenderLabel($gender)
    {
        $genders = $this->getGenders();
        if(isset($genders[$gender]))
            return $genders[$gender];

        return $gender;
    }

    /**
     * Return genders list
     *
     * @return array
     */
    public function getGenders()
    {
        return array(
            'u' => Yii::t('common', 'Unisex'),
            'm' => Yii::t('common', 'Male'),
            'f' => Yii::t('common', 'Female'),
        );
    }

    /**
     * Update user roles
     *
     * @throws CException
     * @param array $roles Array of auth items
     * @return bool
     */
    public function updateRoles($roles)
    {
        /** @var $am CAuthManager */
        $am = Yii::app()->authManager;
        $actRoles = $am->getAuthAssignments($this->id);
        $availRoles = $am->getAuthItems(2);

        $transaction = $this->dbConnection->beginTransaction();
        try
        {
            //  revoke roles
            foreach($actRoles as $role => $assignment)
            {
                if(in_array($role, $roles))
                    continue;

                $am->revoke($role, $this->id);
            }

            //  assign roles
            foreach($roles as $role)
            {
                if(!isset($availRoles[$role]))
                    continue;

                if(!isset($actRoles[$role]))
                    $am->assign($role, $this->id);
            }
            $transaction->commit();
        }
        catch(CException $e)
        {
            $transaction->rollBack();
            throw new CException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }

        return true;
    }

    /**
     * Get user display name
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->email;
    }

    /**
     * List users
     *
     * @param array $filterKeys
     * @return array
     */
    public function listData($filterKeys = array())
    {
        $data = $this;
        if($filterKeys)
            $data = $data->findAllByPk(array('id' => $filterKeys));
        else
            $data = $data->findAll();
        $this->resetScope();

        return CHtml::listData((array)$data, 'id', 'displayName');
    }

    /**
     * Check user password equal entered one
     *
     * @param string $password
     * @return bool
     */
    public function checkPass($password)
    {
        $pwdHasher = new PasswordHash(8, false);

        return $pwdHasher->CheckPassword($password, $this->password);
    }

    /**
     * Get roles list
     * Excluded guest and authenticated
     *
     * @return array
     */
    public static function getRoleList()
    {
        return Rights::getAuthItemSelectOptions(CAuthItem::TYPE_ROLE, array(
            'authenticated', 'guest'
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     *
     * @param string $className active record class name.
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
        return '{{user}}';
    }

    /**
     * User role field
     *
     * @return array
     */
    public function getRole()
    {
        return Rights::getAssignedRoles($this->id, false);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('email', 'required'),
            array('status, sort, discount', 'numerical', 'integerOnly' => true),
            array('login, email', 'length', 'max' => 32),
            array('password', 'length', 'min' => 6),
            array('password', 'required', 'on' => 'create'),
            array('display_name, camefrom', 'length', 'max' => 64),
            array('name, servicename, position, phone, address, image_id, signature_id, delivery_addr, detail_text, sort', 'safe'),
            array('image_id, signature_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
            array('id, login, email, display_name, name, servicename, phone, delivery_addr, status, order_count, first_buy, date_first, date_last, orders_by_period, orders_date_first, orders_date_last', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'authItems' => array(self::MANY_MANY, 'AuthItem', '{{auth_assignment}}(userid, itemname)'),
            'authLogs' => array(self::HAS_MANY, 'AuthLog', 'user_id'),
            'image' => array(self::BELONGS_TO, 'File', 'image_id'),
            'signature' => array(self::BELONGS_TO, 'File', 'signature_id'),
            'userUsertypes' => array(self::MANY_MANY, 'Usertype', '{{user_usertype}}(user_id, usertype_id)', 'together' => true),
        );
    }

    public function relatedCache()
    {
        return array_merge(parent::relatedCache(), array('Auth'));
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'login' => Yii::t('backend', 'Login'),
            'password' => Yii::t('backend', 'Password'),
            'email' => Yii::t('backend', 'Email'),
            'display_name' => Yii::t('backend', 'Display Name'),
            'name' => Yii::t('backend', 'Name'),
            'servicename' => Yii::t('backend', 'Service Name'),
            'position' => Yii::t('backend', 'Position'),
            'phone' => Yii::t('backend', 'Phone'),
            'address' => Yii::t('backend', 'Address'),
            'status' => Yii::t('backend', 'Status'),
            'detail_text' => Yii::t('backend', 'Description'),
            'delivery_addr' => Yii::t('backend', 'Delivery Address'),
            'sort' => Yii::t('backend', 'Sort'),
            'orders' => Yii::t('backend', 'Orders'),
            'discount' => Yii::t('backend', 'Discount'),
            'authItems' => Yii::t('backend', 'Role'),
            'order_count' => Yii::t('backend', 'Orders'),
            'first_buy' => Yii::t('backend', 'First Buy'),
            'orders_by_period' => Yii::t('backend', 'Orders by period'),
            'camefrom' => Yii::t('backend', 'Came from'),
            'image_id'=> Yii::t('backend', 'Image'),
            'signature_id'=> Yii::t('backend', 'Signature'),
        );
    }

    /**
     * User password hash string
     *
     * @param string $data Raw password
     * @return string Hash
     */
    public function passHash($data)
    {
        $pwdHasher = new PasswordHash(8, false);

        return $pwdHasher->HashPassword($data);
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        #count_orders
        $order_table = Order::model()->tableName();
        $order_count_sql = "(select count(*) from `".$order_table."` as pt3 where pt3.user_id = t.id)";

        #first_buy
        $first_buy_sql = "(select min(pt4.date_create) from `".$order_table."` as pt4 where pt4.user_id = t.id)";


        if((isset($this->orders_date_first) && trim($this->orders_date_first) != "") && (isset($this->orders_date_last) && trim($this->orders_date_last) != "")){
            $orders_by_period_sql = "(select count(*) from `".$order_table."` as pt7 where pt7.user_id = t.id AND pt7.date_create between '".$this->orders_date_first."' AND '".$this->orders_date_last."')";
            //$criteria->join = "INNER JOIN `".$order_table."` AS pt8 where pt8.user_id = t.id";
        }else{
            $orders_by_period_sql = "(select count(*) from `".$order_table."` as pt7 where pt7.user_id = t.id)";
            $criteria->compare($orders_by_period_sql, $this->orders_by_period);
        }

        $criteria->select = array(
            '*',
            $order_count_sql . " as order_count",
            $first_buy_sql . " as first_buy",
            $orders_by_period_sql . " as orders_by_period",
        );

        if((isset($this->date_first) && trim($this->date_first) != "") && (isset($this->date_last) && trim($this->date_last) != "")){
            $criteria->addBetweenCondition('(select min(pt6.date_create) from `'.$order_table.'` as pt6 where pt6.user_id = t.id)', ''.$this->date_first.'', ''.$this->date_last.'');
        }

        $criteria->compare($order_count_sql, $this->order_count);
        $criteria->compare($order_count_sql, $this->order_count);
        $criteria->compare($orders_by_period_sql, $this->orders_by_period);
        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.login', $this->login, true);
        $criteria->compare('t.password', $this->password, true);
        $criteria->compare('t.email', $this->email, true);
        $criteria->compare('t.display_name', $this->display_name, true);
        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('t.servicename', $this->servicename, true);
        $criteria->compare('t.phone', $this->phone, true);
        $criteria->compare('t.address', $this->address);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('t.discount', $this->status);
        $criteria->compare('t.camefrom', $this->camefrom);
        $criteria->compare('t.position', $this->position);



        return parent::searchInit($criteria);
    }

    /**
     * Sort scope
     *
     * @param string $column Order column
     * @return User
     */
    public function sort($column = 'login')
    {
        return parent::sort($column);
    }

    /**
     * Generate random password
     *
     * @param int $length Length of password
     * @return string
     */
    public static function generatePassword($length = 10)
    {
        $charset = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz0123456789-_';
        $charsetSize = strlen($charset) - 1;

        $password = '';
        foreach(range(1, $length) as $_)
            $password .= $charset{mt_rand(0, $charsetSize)};

        return $password;
    }

    /**
     * Hash password
     *
     * @return bool
     */
    public static function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    protected function beforeSave()
    {
        if(strlen($this->password) > 0 && $this->old_password!=$this->password){
            $passwd = $this->passHash($this->password);

        }else
            $passwd = $this->id ? $this->findByPk($this->id)->password : null;

        $this->setAttribute('password', $passwd);

        $this->phone=preg_replace('/(\W*)/', '', $this->phone);


        if(!$this->login)
            $this->login = null;

        if(!$this->display_name)
            $this->display_name = $this->getDisplayName();

        return parent::beforeSave();
    }
}