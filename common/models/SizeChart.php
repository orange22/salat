<?php
/**
 * This is the model class for table "{{size_chart}}".
 *
 * The followings are the available columns in table '{{size_chart}}':
 * @property integer $id
 * @property integer $image_id
 * @property string $apparel
 *
 * @method SizeChart active
 * @method SizeChart cache($duration = null, $dependency = null, $queryCount = 1)
 * @method SizeChart indexed($column = 'id')
 * @method SizeChart language($lang = null)
 * @method SizeChart select($columns = '*')
 * @method SizeChart limit($limit, $offset = 0)
 * @method SizeChart sort($columns = '')
 *
 * The followings are the available model relations:
 * @property File $image
 */
class SizeChart extends BaseActiveRecord
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
     * @return SizeChart the static model class
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
        return '{{size_chart}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('apparel', 'length', 'max' => 8),
            array('image_id', 'safe'),
            array('image_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),

            array('id, image_id, apparel', 'safe', 'on' => 'search'),
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
            'image_id' => Yii::t('backend', 'Image'),
            'apparel' => Yii::t('backend', 'Apparel'),
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
		$criteria->compare('t.image_id',$this->image_id);
		$criteria->compare('t.apparel',$this->apparel,true);

        return parent::searchInit($criteria);
    }
}