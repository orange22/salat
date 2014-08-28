<?php
/**
 * This is the model class for table "{{page}}".
 *
 * The followings are the available columns in table '{{page}}':
 * @property integer $id
 * @property string $title
 * @property integer $image_id
 * @property string $detail_text
 * @property integer $sort
 * @property integer $status
 *
 * @method Page active
 * @method Page cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Page indexed($column = 'id')
 * @method Page language($lang = null)
 * @method Page select($columns = '*')
 * @method Page limit($limit, $offset = 0)
 * @method Page sort($columns = '')
 *
 * The followings are the available model relations:
 * @property File $image
 */
class Page extends BaseActiveRecord
{

	public function behaviors()
    {
        return CMap::mergeArray(parent::behaviors(), array(
            'e' => array('class' => 'common.models.Entity'),
            'seo' => array('class' => 'common.components.SeoBehavior'),
            'attach' => array(
                'class' => 'common.components.FileAttachBehavior',
                'imageAttributes' => array(
                    'image_id',
                ),
                'fileAttributes' => array(
                ),
            )
        ));
    }

   /*
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
       }*/
   

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Page the static model class
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
        return '{{page}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
         	array('title, code', 'required'),
            array('sort, status, widget', 'numerical', 'integerOnly' => true),
            array('title, code, link', 'length', 'max' => 255),
            array('image_id, detail_text, preview_text', 'safe'),
            array('image_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
        
            array('id, title, image_id, detail_text, sort, status, link', 'safe', 'on' => 'search'),
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
            'detail_text' => Yii::t('backend', 'Detail Text'),
            'preview_text' => Yii::t('backend', 'Preview Text'),
            'sort' => Yii::t('backend', 'Sort'),
            'status' => Yii::t('backend', 'Status'),
            'widget' => Yii::t('backend', 'Widget'),
            'code' => Yii::t('backend', 'Code'),
            'link' => Yii::t('backend', 'Link'),
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
		$criteria->compare('t.detail_text',$this->detail_text,true);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);

        return parent::searchInit($criteria);
    }
}