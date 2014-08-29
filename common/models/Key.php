<?php
/**
 * This is the model class for table "{{key}}".
 *
 * The followings are the available columns in table '{{key}}':
 * @property integer $id
 * @property string $title
 * @property string $token
 * @property integer $user_id
 * @property string $date_create
 *
 * @method Key active
 * @method Key cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Key indexed($column = 'id')
 * @method Key language($lang = null)
 * @method Key select($columns = '*')
 * @method Key limit($limit, $offset = 0)
 * @method Key sort($columns = '')
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Key extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Key the static model class
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
        return '{{key}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('token, user_id, date_create', 'required'),
            array('user_id', 'numerical', 'integerOnly' => true),
            array('title, token', 'length', 'max' => 55),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
        
            array('id, title, token, user_id, date_create', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
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
            'title' => Yii::t('backend', 'Title'),
            'token' => Yii::t('backend', 'Token'),
            'user_id' => Yii::t('backend', 'User'),
            'date_create' => Yii::t('backend', 'Date Create'),
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
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.token',$this->token,true);
		$criteria->compare('t.user_id',$this->user_id);
		$criteria->compare('t.date_create',$this->date_create,true);

		$criteria->with = array('user');

        return parent::searchInit($criteria);
    }
}