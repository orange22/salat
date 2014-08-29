<?php
/**
 * This is the model class for table "{{slide}}".
 *
 * The followings are the available columns in table '{{slide}}':
 * @property integer $id
 * @property integer $pid
 * @property string $language_id
 * @property integer $image_id
 * @property string $text
 * @property string $url
 * @property integer $sort
 * @property integer $status
 *
 * @method Slide active
 * @method Slide cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Slide indexed($column = 'language_id')
 * @method Slide language($lang = null)
 * @method Slide select($columns = '*')
 * @method Slide limit($limit, $offset = 0)
 * @method Slide sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Language $language
 * @property File $image
 */
class Slide extends LangActiveRecord
{
    public function fixedAttributes()
    {
        return CMap::mergeArray(parent::fixedAttributes(), array(
            'image_id',
            'url',
        ));
    }

    public function behaviors()
    {
        return array(
            'attach' => array(
                'class' => 'common.components.FileAttachBehavior',
                'imageAttributes' => array(
                    'image_id',
                ),
                'fileAttributes' => array(
                ),
            )
        );
    }

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Slide the static model class
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
        return '{{slide}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('sort, status', 'numerical', 'integerOnly' => true),
            array('image_id, text, url', 'safe'),
            array('image_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),

            array('id, pid, language_id, image_id, text, url, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'language' => array(self::BELONGS_TO, 'Language', 'language_id'),
            'image' => array(self::BELONGS_TO, 'File', 'image_id'),
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
            'image_id' => Yii::t('backend', 'Image'),
            'text' => Yii::t('backend', 'Text'),
            'url' => Yii::t('backend', 'Url'),
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
		$criteria->compare('t.image_id',$this->image_id);
		$criteria->compare('t.text',$this->text,true);
		$criteria->compare('t.url',$this->url,true);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);

        return parent::searchInit($criteria);
    }
}