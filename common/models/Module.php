<?php
/**
 * This is the model class for table "{{module}}".
 *
 * The followings are the available columns in table '{{module}}':
 * @property integer $id
 * @property integer $pid
 * @property string $language_id
 * @property string $title
 * @property string $type
 * @property string $code
 * @property integer $sort
 * @property integer $status
 *
 * @method Module active
 * @method Module cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Module indexed($column = 'language_id')
 * @method Module language($lang = null)
 * @method Module select($columns = '*')
 * @method Module limit($limit, $offset = 0)
 * @method Module sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Language $language
 */
class Module extends LangActiveRecord
{
    public function fixedAttributes()
    {
        return CMap::mergeArray(parent::fixedAttributes(), array(
            'type',
            'code',
        ));
    }

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Module the static model class
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
        return '{{module}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('title, type, code', 'required'),
            array('sort, status', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 256),
            array('type', 'length', 'max' => 12),
            array('code', 'length', 'max' => 8),

            array('id, pid, language_id, title, type, code, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'language' => array(self::BELONGS_TO, 'Language', 'language_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'pid' => 'PID',
            'language_id' => Yii::t('backend', 'Language'),
            'title' => Yii::t('backend', 'Title'),
            'type' => Yii::t('backend', 'Type'),
            'code' => Yii::t('backend', 'Code'),
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
		$criteria->compare('t.pid',$this->pid);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.type',$this->type,true);
		$criteria->compare('t.code',$this->code,true);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);

        return parent::searchInit($criteria);
    }
}