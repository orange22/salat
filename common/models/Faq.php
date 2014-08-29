<?php
/**
 * This is the model class for table "{{faq}}".
 *
 * The followings are the available columns in table '{{faq}}':
 * @property integer $id
 * @property string $title
 * @property string $answer
 * @property integer $status
 * @property integer $sort
 *
 * @method Faq active
 * @method Faq cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Faq indexed($column = 'id')
 * @method Faq language($lang = null)
 * @method Faq select($columns = '*')
 * @method Faq limit($limit, $offset = 0)
 * @method Faq sort($columns = '')
 */
class Faq extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Faq the static model class
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
        return '{{faq}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('status, sort', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('answer', 'safe'),
        
            array('id, title, answer, status, sort', 'safe', 'on' => 'search'),
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
            'answer' => Yii::t('backend', 'Answer'),
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
		$criteria->compare('t.answer',$this->answer,true);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.sort',$this->sort);

        return parent::searchInit($criteria);
    }
}