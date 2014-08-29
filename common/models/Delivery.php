<?php
/**
 * This is the model class for table "{{delivery}}".
 *
 * The followings are the available columns in table '{{delivery}}':
 * @property integer $id
 * @property string $title
 * @property string $detail_text
 * @property integer $status
 * @property integer $sort
 *
 * @method Delivery active
 * @method Delivery cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Delivery indexed($column = 'id')
 * @method Delivery language($lang = null)
 * @method Delivery select($columns = '*')
 * @method Delivery limit($limit, $offset = 0)
 * @method Delivery sort($columns = '')
 */
class Delivery extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Delivery the static model class
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
        return '{{delivery}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('status, sort', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('detail_text', 'safe'),
        
            array('id, title, detail_text, status, sort', 'safe', 'on' => 'search'),
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
            'title' => Yii::t('backend', 'Title'),
            'detail_text' => Yii::t('backend', 'Detail Text'),
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
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.detail_text',$this->detail_text,true);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.sort',$this->sort);

        return parent::searchInit($criteria);
    }
}