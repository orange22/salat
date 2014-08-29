<?php
/**
 * This is the model class for table "{{ingredient}}".
 *
 * The followings are the available columns in table '{{ingredient}}':
 * @property integer $id
 * @property string $title
 * @property integer $status
 * @property integer $sort
 * @property integer $image_id
 * @property string $dimension
 *
 * @method Ingredient active
 * @method Ingredient cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Ingredient indexed($column = 'id')
 * @method Ingredient language($lang = null)
 * @method Ingredient select($columns = '*')
 * @method Ingredient limit($limit, $offset = 0)
 * @method Ingredient sort($columns = '')
 *
 * The followings are the available model relations:
 * @property CourseIngredient[] $courseIngredients
 * @property File $image
 */
class Ingredient extends BaseActiveRecord
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
     * @return Ingredient the static model class
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
        return '{{ingredient}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('status, sort', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            array('dimension', 'length', 'max' => 55),
            array('image_id', 'safe'),
            array('image_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
        
            array('id, title, status, sort, image_id, dimension', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'courseIngredients' => array(self::HAS_MANY, 'CourseIngredient', 'ingredient_id'),
            'image' => array(self::BELONGS_TO, 'File', 'image_id', 'alias'=>"ingredientImage"),
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
            'image_id' => Yii::t('backend', 'Image'),
            'dimension' => Yii::t('backend', 'Dimension'),
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
		$criteria->compare('t.image_id',$this->image_id);
		$criteria->compare('t.dimension',$this->dimension,true);

        return parent::searchInit($criteria);
    }
}