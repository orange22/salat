<?php
/**
 * This is the model class for table "{{charity}}".
 *
 * The followings are the available columns in table '{{charity}}':
 * @property integer $id
 * @property string $title
 * @property integer $value
 * @property integer $sort
 * @property integer $status
 *
 * @method Charity active
 * @method Charity cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Charity indexed($column = 'id')
 * @method Charity language($lang = null)
 * @method Charity select($columns = '*')
 * @method Charity limit($limit, $offset = 0)
 * @method Charity sort($columns = '')
 *
 * The followings are the available model relations:
 * @property CharityUser[] $charityUsers
 */
class Charity extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Charity the static model class
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
        return '{{charity}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('title, value', 'required'),
            array('value, sort, status', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
        
            array('id, title, value, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'charityUsers' => array(self::HAS_MANY, 'CharityUser', 'charity_id'),
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
            'value' => Yii::t('backend', 'Value'),
            'sort' => Yii::t('backend', 'Sort'),
            'status' => Yii::t('backend', 'Status'),
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
		$criteria->compare('t.value',$this->value);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);

        return parent::searchInit($criteria);
    }
}