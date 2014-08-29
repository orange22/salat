<?php
/**
 * This is the model class for table "{{subscriber}}".
 *
 * The followings are the available columns in table '{{subscriber}}':
 * @property integer $id
 * @property string $email
 * @property integer $status
 * @property integer $sort
 *
 * @method Subscriber active
 * @method Subscriber cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Subscriber indexed($column = 'id')
 * @method Subscriber language($lang = null)
 * @method Subscriber select($columns = '*')
 * @method Subscriber limit($limit, $offset = 0)
 * @method Subscriber sort($columns = '')
 */
class Subscriber extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Subscriber the static model class
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
        return '{{subscriber}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('email', 'required'),
            array('status, sort', 'numerical', 'integerOnly' => true),
            array('email', 'length', 'max' => 55),
        
            array('id, email, status, sort', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
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
            'email' => Yii::t('backend', 'Email'),
            'status' => Yii::t('backend', 'Status'),
            'sort' => Yii::t('backend', 'Sort'),
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
		$criteria->compare('t.email',$this->email,true);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.sort',$this->sort);

        return parent::searchInit($criteria);
    }
}