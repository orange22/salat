<?php
/**
 * This is the model class for table "{{video}}".
 *
 * The followings are the available columns in table '{{video}}':
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property integer $videotype_id
 * @property integer $course_id
 * @property integer $sort
 * @property integer $status
 * @property integer $image_id
 *
 * @method Video active
 * @method Video cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Video indexed($column = 'id')
 * @method Video language($lang = null)
 * @method Video select($columns = '*')
 * @method Video limit($limit, $offset = 0)
 * @method Video sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Course $course
 * @property File $image
 * @property Videotype $videotype
 */
class Video extends BaseActiveRecord
{

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
     * @return Video the static model class
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
        return '{{video}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('title, url, videotype_id, course_id', 'required'),
            array('videotype_id, course_id, sort, status', 'numerical', 'integerOnly' => true),
            array('title, url', 'length', 'max' => 255),
            array('image_id', 'safe'),
            array('image_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
            array('videotype_id', 'exist', 'className' => 'Videotype', 'attributeName' => 'id'),
            array('course_id', 'exist', 'className' => 'Course', 'attributeName' => 'id'),
        
            array('id, title, url, videotype_id, course_id, sort, status, image_id', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'course' => array(self::BELONGS_TO, 'Course', 'course_id'),
            'image' => array(self::BELONGS_TO, 'File', 'image_id'),
            'videotype' => array(self::BELONGS_TO, 'Videotype', 'videotype_id'),
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
            'url' => Yii::t('backend', 'Url'),
            'videotype_id' => Yii::t('backend', 'Videotype'),
            'course_id' => Yii::t('backend', 'Course'),
            'sort' => Yii::t('backend', 'Sort'),
            'status' => Yii::t('backend', 'Status'),
            'image_id' => Yii::t('backend', 'Image'),
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
		$criteria->compare('t.url',$this->url,true);
		$criteria->compare('t.videotype_id',$this->videotype_id);
		$criteria->compare('t.course_id',$this->course_id);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.image_id',$this->image_id);

		$criteria->with = array('course', 'videotype');

        return parent::searchInit($criteria);
    }
}