<?php
/**
 * This is the model class for table "{{product}}".
 *
 * The followings are the available columns in table '{{product}}':
 * @property integer $id
 * @property string $title
 * @property integer $category_id
 * @property integer $image_id
 * @property string $detail_text
 * @property double $price
 * @property integer $weight
 * @property string $date_create
 * @property integer $sort
 * @property integer $status
 *
 * @method Product active
 * @method Product cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Product indexed($column = 'id')
 * @method Product language($lang = null)
 * @method Product select($columns = '*')
 * @method Product limit($limit, $offset = 0)
 * @method Product sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Category $category
 * @property File $image
 */
class Product extends BaseActiveRecord
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
     * @return Product the static model class
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
        return '{{product}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('title, category_id', 'required'),
            array('category_id, weight, sort, status', 'numerical', 'integerOnly' => true),
            array('price', 'numerical'),
            array('title', 'length', 'max' => 255),
            array('image_id, detail_text', 'safe'),
            array('image_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
            array('category_id', 'exist', 'className' => 'Category', 'attributeName' => 'id'),
        
            array('id, title, category_id, image_id, detail_text, price, weight, sort, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
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
            'category_id' => Yii::t('backend', 'Category'),
            'image_id' => Yii::t('backend', 'Image'),
            'detail_text' => Yii::t('backend', 'Detail Text'),
            'price' => Yii::t('backend', 'Price'),
            'weight' => Yii::t('backend', 'Weight'),
            'date_create' => Yii::t('backend', 'Date Create'),
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
		$criteria->compare('t.category_id',$this->category_id);
		$criteria->compare('t.image_id',$this->image_id);
		$criteria->compare('t.detail_text',$this->detail_text,true);
		$criteria->compare('t.price',$this->price);
		$criteria->compare('t.weight',$this->weight);
		$criteria->compare('t.date_create',$this->date_create,true);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);

		$criteria->with = array('category');

        return parent::searchInit($criteria);
    }
}