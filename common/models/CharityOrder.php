<?php
/**
 * This is the model class for table "{{charity_order}}".
 *
 * The followings are the available columns in table '{{charity_order}}':
 * @property integer $id
 * @property integer $charity_id
 * @property integer $order_id
 *
 * @method CharityOrder active
 * @method CharityOrder cache($duration = null, $dependency = null, $queryCount = 1)
 * @method CharityOrder indexed($column = 'id')
 * @method CharityOrder language($lang = null)
 * @method CharityOrder select($columns = '*')
 * @method CharityOrder limit($limit, $offset = 0)
 * @method CharityOrder sort($columns = '')
 *
 * The followings are the available model relations:
 * @property Order $order
 * @property Charity $charity
 */
class CharityOrder extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return CharityOrder the static model class
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
        return '{{charity_order}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('charity_id, order_id', 'required'),
            array('charity_id, order_id', 'numerical', 'integerOnly' => true),
            array('charity_id', 'exist', 'className' => 'Charity', 'attributeName' => 'id'),
            array('order_id', 'exist', 'className' => 'Order', 'attributeName' => 'id'),
        
            array('id, charity_id, order_id', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
            'charity' => array(self::BELONGS_TO, 'Charity', 'charity_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'charity_id' => Yii::t('backend', 'Charity'),
            'order_id' => Yii::t('backend', 'Order'),
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
		$criteria->compare('t.charity_id',$this->charity_id);
		$criteria->compare('t.order_id',$this->order_id);

		$criteria->with = array('order', 'charity');

        return parent::searchInit($criteria);
    }
    public function updateForOrder($id, $newData = array())
    {
        $buff = array();
        // rid of possibly duplicated size quantitys, use last one

        foreach($newData as $item)
            $buff[(int)$item] =$item;

        $newData = $buff;

        if(empty($newData))
            return self::model()->deleteAllByAttributes(array('order_id' => $id));

        $o = 0;
        $delete = array();

        // update existing product info with new quantities, prices
        /** @var $curData ProductInfo[] */
        $curData = self::model()->findAllByAttributes(array('order_id' => $id));
        foreach($curData as $item)
        {
            if(!isset($newData[$item->charity_id]))
            {
                $delete[] = $item->charity_id;
                continue;
            }
            if(isset($newData[$item->charity_id])){
                unset($newData[$item->charity_id]);
                ++$o;
            }
        }

        // delete info
        self::model()->deleteAllByAttributes(array('order_id' => $id, 'charity_id' => $delete));



        // add new info
        $model = new self();
        foreach($newData as $charity_id)
        {
            $model->order_id = $id;
            $model->charity_id = $charity_id;
            if($model->save(false))
            {
                ++$o;
                $model->id = null;
                $model->setIsNewRecord(true);
            }
        }

        return $o;
    }

}