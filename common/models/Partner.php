<?php
/**
 * This is the model class for table "{{partner}}".
 *
 * The followings are the available columns in table '{{partner}}':
 * @property integer $id
 * @property string $title
 * @property integer $image_id
 * @property string $url
 * @property integer $sort
 * @property integer $status
 *
 * @method Partner active
 * @method Partner cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Partner indexed($column = 'id')
 * @method Partner language($lang = null)
 * @method Partner select($columns = '*')
 * @method Partner limit($limit, $offset = 0)
 * @method Partner sort($columns = '')
 *
 * The followings are the available model relations:
 * @property File $image
 */
class Partner extends BaseActiveRecord
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
     * @return Partner the static model class
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
        return '{{partner}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('sort, status', 'numerical', 'integerOnly' => true),
            array('title, url', 'length', 'max' => 255),
            array('image_id', 'safe'),
            array('image_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
        
            array('id, title, image_id, url, sort, status', 'safe', 'on' => 'search'),
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
            'image_id' => Yii::t('backend', 'Image'),
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
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.image_id',$this->image_id);
		$criteria->compare('t.url',$this->url,true);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);

        return parent::searchInit($criteria);
    }
}