<?php
/**
 * This is the model class for table "{{product_size}}".
 *
 * The followings are the available columns in table '{{product_size}}':
 * @property integer $id
 * @property integer $product_pid
 * @property integer $size_id
 * @property integer $quantity
 *
 * @method ProductSize active
 * @method ProductSize cache($duration = null, $dependency = null, $queryCount = 1)
 * @method ProductSize indexed($column = 'id')
 * @method ProductSize language($lang = null)
 * @method ProductSize select($columns = '*')
 * @method ProductSize limit($limit, $offset = 0)
 * @method ProductSize sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property Size $size
 */
class ProductSize extends BaseActiveRecord
{
    /**
     * Update product sizes
     *
     * @param int $pid Product PID
     * @param array $newData New sizes data
     * @return int Number of updated+inserted entries
     * @throws CHttpException
     */
    public function updateForProduct($pid, $newData = array())
    {
        if(empty($newData))
            return self::model()->deleteAllByAttributes(array('product_pid' => $pid));

        $buff = array();
        // rid of possibly duplicated size values, use last one
        foreach($newData as $item)
            $buff[$item['size_id']] = $item['quantity'];
        $newData = $buff;

        $o = 0;
        $delete = array();

        // update existing product sizes with new quantities
        /** @var $curData ProductSize[] */
        $curData = self::model()->findAllByAttributes(array('product_pid' => $pid));
        foreach($curData as $item)
        {
            if(!isset($newData[$item['size_id']]))
            {
                $delete[] = $item['size_id'];
                continue;
            }

            if((int)$newData[$item['size_id']] === (int)$item->quantity)
            {
                unset($newData[$item['size_id']]);
                continue;
            }

            $item->quantity = (int)$newData[$item['size_id']];
            $item->update(array('quantity'));
            unset($newData[$item['size_id']]);
            ++$o;
        }

        // delete sizes
        self::model()->deleteAllByAttributes(array('product_pid' => $pid, 'size_id' => $delete));

        if(count($newData) != Size::model()->countByAttributes(array('id' => array_keys($newData))))
        {
            throw new CHttpException(500, Yii::t('backend', 'Invalid size values.'));
        }

        // add new size quantities
        $model = new self();
        foreach($newData as $sizeId => $qty)
        {
            $model->product_pid = $pid;
            $model->size_id = $sizeId;
            $model->quantity = $qty;
            if($model->save(false))
                ++$o;
        }

        return $o;
    }

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return ProductSize the static model class
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
        return '{{product_size}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('product_pid, size_id', 'required'),
            array('product_pid, size_id', 'numerical', 'integerOnly' => true),
            array('quantity', 'numerical', 'min' => 0, 'integerOnly' => true),
            array('product_pid', 'exist', 'className' => 'Product', 'attributeName' => 'pid'),
            array('size_id', 'exist', 'className' => 'Size', 'attributeName' => 'id'),

            array('id, product_pid, size_id, quantity', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'product' => array(self::BELONGS_TO, 'Product', 'product_pid', 'scopes' => array('language')),
            'size' => array(self::BELONGS_TO, 'Size', 'size_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'product_pid' => Yii::t('backend', 'Product'),
            'size_id' => Yii::t('backend', 'Size'),
            'quantity' => Yii::t('backend', 'Quantity'),
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
		$criteria->compare('t.size_id',$this->size_id);
		$criteria->compare('t.quantity',$this->quantity);

		$criteria->with = array('product', 'size');

        return parent::searchInit($criteria);
    }
}