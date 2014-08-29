<?php
/**
 * This is the model class for table "{{cookware}}".
 *
 * The followings are the available columns in table '{{cookware}}':
 * @property integer $id
 * @property string $title
 * @property integer $image_id
 * @property integer $status
 * @property integer $sort
 *
 * @method Cookware active
 * @method Cookware cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Cookware indexed($column = 'id')
 * @method Cookware language($lang = null)
 * @method Cookware select($columns = '*')
 * @method Cookware limit($limit, $offset = 0)
 * @method Cookware sort($columns = '')
 *
 * The followings are the available model relations:
 * @property File $image
 * @property DishCookware[] $dishCookwares
 */
class Cookware extends BaseActiveRecord
{

    public function behaviors()
    {
        return array(
            'attach' => array(
                'class' => 'common.components.FileAttachBehavior',
                'imageAttributes' => array(
                    'image_id','bigimage_id',
                ),
                'fileAttributes' => array(
                ),
            )
        );
    }

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Cookware the static model class
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
        return '{{cookware}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('status, sort', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('image_id,bigimage_id', 'safe'),
            array('image_id,bigimage_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
        
            array('id, title, image_id, bigimage_id, status, sort', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'image' => array(self::BELONGS_TO, 'File', 'image_id'),
            'bigimage' => array(self::BELONGS_TO, 'File', 'bigimage_id'),
            'dishCookwares' => array(self::HAS_MANY, 'DishCookware', 'cookware_id'),
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
            'image_id' => Yii::t('backend', 'Image'),
            'bigimage_id' => Yii::t('backend', 'Big image'),
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
		$criteria->compare('t.image_id',$this->image_id);
        $criteria->compare('t.bigimage_id',$this->bigimage_id);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.sort',$this->sort);

        return parent::searchInit($criteria);
    }
}