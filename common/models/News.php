<?php
/**
 * This is the model class for table "{{news}}".
 *
 * The followings are the available columns in table '{{news}}':
 * @property integer $id
 * @property integer $pid
 * @property string $language_id
 * @property integer $thumb_id
 * @property string $title
 * @property string $short_text
 * @property string $full_text
 * @property string $date_created
 * @property integer $status
 *
 * @method News active
 * @method News cache($duration = null, $dependency = null, $queryCount = 1)
 * @method News indexed($column = 'language_id')
 * @method News language($lang = null)
 * @method News select($columns = '*')
 * @method News limit($limit, $offset = 0)
 * @method News sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Language $language
 * @property File $thumb
 */
class News extends LangActiveRecord
{
    public function fixedAttributes()
    {
        return CMap::mergeArray(parent::fixedAttributes(), array(
            'thumb_id',
            'date_created',
        ));
    }

    public function behaviors()
    {
        return array(
            'attach' => array(
                'class' => 'common.components.FileAttachBehavior',
                'imageAttributes' => array(
                    'thumb_id',
                ),
                'fileAttributes' => array(
                ),
            )
        );
    }

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return News the static model class
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
        return '{{news}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('status', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 256),
            array('thumb_id, short_text, full_text, date_created', 'safe'),
            array('thumb_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),

            array('id, pid, language_id, thumb_id, title, short_text, full_text, date_created, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'language' => array(self::BELONGS_TO, 'Language', 'language_id'),
            'thumb' => array(self::BELONGS_TO, 'File', 'thumb_id'),
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
            'thumb_id' => Yii::t('backend', 'Thumb'),
            'title' => Yii::t('backend', 'Title'),
            'short_text' => Yii::t('backend', 'Short Text'),
            'full_text' => Yii::t('backend', 'Full Text'),
            'date_created' => Yii::t('backend', 'Date Created'),
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
		$criteria->compare('t.thumb_id',$this->thumb_id);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.short_text',$this->short_text,true);
		$criteria->compare('t.full_text',$this->full_text,true);
		$criteria->compare('t.date_created',$this->date_created,true);
		$criteria->compare('t.status',$this->status);

        return parent::searchInit($criteria);
    }
}