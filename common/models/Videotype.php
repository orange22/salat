<?php
/**
 * This is the model class for table "{{videotype}}".
 *
 * The followings are the available columns in table '{{videotype}}':
 * @property integer $id
 * @property string $title
 * @property integer $status
 * @property integer $sort
 *
 * @method Videotype active
 * @method Videotype cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Videotype indexed($column = 'id')
 * @method Videotype language($lang = null)
 * @method Videotype select($columns = '*')
 * @method Videotype limit($limit, $offset = 0)
 * @method Videotype sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Video[] $videos
 */
class Videotype extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Videotype the static model class
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
        return '{{videotype}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('status, sort', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
        
            array('id, title, status, sort', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'videos' => array(self::HAS_MANY, 'Video', 'videotype_id'),
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
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.sort',$this->sort);

        return parent::searchInit($criteria);
    }
}