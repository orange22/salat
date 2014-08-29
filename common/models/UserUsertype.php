<?php
/**
 * This is the model class for table "{{user_usertype}}".
 *
 * The followings are the available columns in table '{{user_usertype}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $usertype_id
 *
 * @method UserUsertype active
 * @method UserUsertype cache($duration = null, $dependency = null, $queryCount = 1)
 * @method UserUsertype indexed($column = 'id')
 * @method UserUsertype language($lang = null)
 * @method UserUsertype select($columns = '*')
 * @method UserUsertype limit($limit, $offset = 0)
 * @method UserUsertype sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Usertype $usertype
 * @property User $user
 */
class UserUsertype extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return UserUsertype the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
	
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{user_usertype}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('user_id, usertype_id', 'required'),
            array('user_id, usertype_id', 'numerical', 'integerOnly' => true),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
            array('usertype_id', 'exist', 'className' => 'Usertype', 'attributeName' => 'id'),
        
            array('id, user_id, usertype_id', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'usertype' => array(self::BELONGS_TO, 'Usertype', 'usertype_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user_id' => Yii::t('backend', 'User'),
            'usertype_id' => Yii::t('backend', 'Usertype'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.user_id',$this->user_id);
		$criteria->compare('t.usertype_id',$this->usertype_id);

		$criteria->with = array('usertype', 'user');

        return parent::searchInit($criteria);
    }
}