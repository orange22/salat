<?php
/**
 * This is the model class for table "{{designer}}".
 *
 * The followings are the available columns in table '{{designer}}':
 * @property integer $id
 * @property string $title
 * @property integer $status
 *
 * @method Designer active
 * @method Designer cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Designer indexed($column = 'id')
 * @method Designer language($lang = null)
 * @method Designer select($columns = '*')
 * @method Designer limit($limit, $offset = 0)
 *
 * The followings are the available model relations:
 * @property Product[] $products
 */
class Designer extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Designer the static model class
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
        return '{{designer}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('title', 'required'),
            array('status', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 256),

            array('id, title, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'products' => array(self::HAS_MANY, 'Product', 'designer_id'),
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

        return parent::searchInit($criteria);
    }

    public function sort($columns = 'title')
    {
        return parent::sort($columns);
    }
}