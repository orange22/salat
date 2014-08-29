<?php
/**
 * This is the model class for table "{{teaser}}".
 *
 * The followings are the available columns in table '{{teaser}}':
 * @property integer $id
 * @property string $title
 * @property string $link
 * @property integer $image_id
 * @property integer $status
 * @property integer $sort
 *
 * @method Teaser active
 * @method Teaser cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Teaser indexed($column = 'id')
 * @method Teaser language($lang = null)
 * @method Teaser select($columns = '*')
 * @method Teaser limit($limit, $offset = 0)
 * @method Teaser sort($columns = '')
 *
 * The followings are the available model relations:
 * @property File $image
 */
class Teaser extends BaseActiveRecord
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
     * @return Teaser the static model class
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
        return '{{teaser}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('status, sort', 'numerical', 'integerOnly' => true),
            array('title, link', 'length', 'max' => 255),
            array('image_id, video', 'safe'),
            array('image_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
        
            array('id, title, link, image_id, status, sort', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
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
            'link' => Yii::t('backend', 'Link'),
            'image_id' => Yii::t('backend', 'Image'),
            'status' => Yii::t('backend', 'Status'),
            'sort' => Yii::t('backend', 'Sort'),
            'video' => Yii::t('backend', 'Video'),
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
		$criteria->compare('t.link',$this->link,true);
		$criteria->compare('t.image_id',$this->image_id);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.video',$this->video);
		

        return parent::searchInit($criteria);
    }
}