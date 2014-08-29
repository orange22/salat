<?php
/**
 * This is the model class for table "{{step}}".
 *
 * The followings are the available columns in table '{{step}}':
 * @property integer $id
 * @property string $title
 * @property string $preview_text
 * @property string $detail_text
 * @property integer $step
 * @property string $advice
 * @property integer $user_id
 * @property integer $image_id
 * @property integer $status
 * @property integer $sort
 * @property integer $course_id
 *
 * @method Step active
 * @method Step cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Step indexed($column = 'id')
 * @method Step language($lang = null)
 * @method Step select($columns = '*')
 * @method Step limit($limit, $offset = 0)
 * @method Step sort($columns = '')
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Dish $dish
 * @property File $image
 */
class Step extends BaseActiveRecord
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
     * @return Step the static model class
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
        return '{{step}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('step, course_id', 'required'),
            array('step, user_id, status, sort, course_id', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('preview_text, detail_text, advice, image_id', 'safe'),
            array('image_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
            array('course_id', 'exist', 'className' => 'Course', 'attributeName' => 'id'),
        
            array('id, title, preview_text, detail_text, step, advice, user_id, image_id, status, sort, course_id', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'course' => array(self::BELONGS_TO, 'Course', 'course_id'),
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
            'title' => Yii::t('backend', 'Title'),
            'preview_text' => Yii::t('backend', 'Preview Text'),
            'detail_text' => Yii::t('backend', 'Detail Text'),
            'step' => Yii::t('backend', 'Step'),
            'advice' => Yii::t('backend', 'Advice'),
            'user_id' => Yii::t('backend', 'User'),
            'image_id' => Yii::t('backend', 'Image'),
            'status' => Yii::t('backend', 'Status'),
            'sort' => Yii::t('backend', 'Sort'),
            'course_id' => Yii::t('backend', 'Course'),
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
		$criteria->compare('t.preview_text',$this->preview_text,true);
		$criteria->compare('t.detail_text',$this->detail_text,true);
		$criteria->compare('t.step',$this->step);
		$criteria->compare('t.advice',$this->advice,true);
		$criteria->compare('t.user_id',$this->user_id);
		$criteria->compare('t.image_id',$this->image_id);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.course_id',$this->course_id);

		$criteria->with = array('user', 'course');

        return parent::searchInit($criteria);
    }
}