<?php
/**
 * This is the model class for table "{{product_image}}".
 *
 * The followings are the available columns in table '{{product_image}}':
 * @property integer $id
 * @property integer $product_pid
 * @property integer $thumb_id
 * @property integer $image_id
 * @property integer $sort
 *
 * @method ProductImage active
 * @method ProductImage cache($duration = null, $dependency = null, $queryCount = 1)
 * @method ProductImage indexed($column = 'id')
 * @method ProductImage language($lang = null)
 * @method ProductImage select($columns = '*')
 * @method ProductImage limit($limit, $offset = 0)
 * @method ProductImage sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property File $thumb
 * @property File $image
 */
class ProductImage extends BaseActiveRecord
{
    /**
     * Update images sorting
     *
     * @param int $productPid Product PID
     * @param string $data JQuery serialized image IDs
     * @return bool
     */
    public function updateSorting($productPid, $data)
    {
        parse_str($data, $parsed);

        if(empty($parsed['img']))
            return false;

        $sql = Yii::app()->db->createCommand(
            'UPDATE {{product_image}} SET sort = :sort WHERE product_pid = :product AND id = :id'
        );
        $sql->prepare();
        foreach($parsed['img'] as $i => $id)
            $sql->execute(array(':sort' => $i + 1, ':id' => $id, ':product' => $productPid));

        $this->refreshCache();

        return true;
    }

    public function behaviors()
    {
        return array(
            'attach' => array(
                'class' => 'common.components.FileAttachBehavior',
                'imageAttributes' => array(
                    'image_id',
                    'thumb_id',
                ),
                'fileAttributes' => array(
                ),
                'forceFileClean' => true
            )
        );
    }

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return ProductImage the static model class
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
        return '{{product_image}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('product_pid', 'required'),
            array('product_pid, sort', 'numerical', 'integerOnly' => true),
            array('thumb_id, image_id', 'safe'),
            array('thumb_id, image_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
            array('product_pid', 'exist', 'className' => 'Product', 'attributeName' => 'pid'),

            array('id, product_pid, thumb_id, image_id, sort', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'product' => array(self::BELONGS_TO, 'Product', 'product_pid'),
            'thumb' => array(self::BELONGS_TO, 'File', 'thumb_id'),
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
            'product_pid' => Yii::t('backend', 'Product Pid'),
            'thumb_id' => Yii::t('backend', 'Thumb'),
            'image_id' => Yii::t('backend', 'Image'),
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
		$criteria->compare('t.product_pid',$this->product_pid);
		$criteria->compare('t.thumb_id',$this->thumb_id);
		$criteria->compare('t.image_id',$this->image_id);
		$criteria->compare('t.sort',$this->sort);

		$criteria->with = array('product');

        return parent::searchInit($criteria);
    }
}