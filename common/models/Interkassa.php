<?php
/**
 * This is the model class for table "{{interkassa}}".
 *
 * The followings are the available columns in table '{{interkassa}}':
 * @property integer $id
 * @property string $title
 * @property integer $ik_shop_id
 * @property double $ik_payment_amount
 * @property integer $ik_payment_id
 * @property string $ik_payment_desc
 * @property string $ik_paysystem_alias
 * @property string $ik_baggage_fields
 * @property string $ik_payment_timestamp
 * @property string $ik_payment_state
 * @property integer $ik_trans_id
 * @property string $ik_currency_exch
 * @property string $ik_fees_payer
 * @property string $ik_sign_hash
 *
 * @method Interkassa active
 * @method Interkassa cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Interkassa indexed($column = 'id')
 * @method Interkassa language($lang = null)
 * @method Interkassa select($columns = '*')
 * @method Interkassa limit($limit, $offset = 0)
 * @method Interkassa sort($columns = '')
 */
class Interkassa extends BaseActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
     * @return Interkassa the static model class
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
        return '{{interkassa}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('ik_shop_id, ik_payment_id, ik_trans_id, title, ik_payment_amount, ik_payment_desc, ik_paysystem_alias, ik_baggage_fields, ik_payment_state, ik_currency_exch, ik_fees_payer, ik_sign_hash', 'length', 'max' => 255),
            array('id, title, ik_shop_id, ik_payment_amount, ik_payment_id, ik_payment_desc, ik_paysystem_alias, ik_baggage_fields, ik_payment_timestamp, ik_payment_state, ik_trans_id, ik_currency_exch, ik_fees_payer, ik_sign_hash', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
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
            'ik_shop_id' => Yii::t('backend', 'Ik_Shop'),
            'ik_payment_amount' => Yii::t('backend', 'Ik_Payment_Amount'),
            'ik_payment_id' => Yii::t('backend', 'Ik_Payment'),
            'ik_payment_desc' => Yii::t('backend', 'Ik_Payment_Desc'),
            'ik_paysystem_alias' => Yii::t('backend', 'Ik_Paysystem_Alias'),
            'ik_baggage_fields' => Yii::t('backend', 'Ik_Baggage Fields'),
            'ik_payment_timestamp' => Yii::t('backend', 'Ik_Payment_Timestamp'),
            'ik_payment_state' => Yii::t('backend', 'Ik_Payment_State'),
            'ik_trans_id' => Yii::t('backend', 'Ik_Trans'),
            'ik_currency_exch' => Yii::t('backend', 'Ik_Currency_Exch'),
            'ik_fees_payer' => Yii::t('backend', 'Ik_Fees_Payer'),
            'ik_sign_hash' => Yii::t('backend', 'Ik_Sign_Hash'),
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
		$criteria->compare('t.ik_shop_id',$this->ik_shop_id);
		$criteria->compare('t.ik_payment_amount',$this->ik_payment_amount);
		$criteria->compare('t.ik_payment_id',$this->ik_payment_id);
		$criteria->compare('t.ik_payment_desc',$this->ik_payment_desc,true);
		$criteria->compare('t.ik_paysystem_alias',$this->ik_paysystem_alias,true);
		$criteria->compare('t.ik_baggage_fields',$this->ik_baggage_fields,true);
		$criteria->compare('t.ik_payment_timestamp',$this->ik_payment_timestamp,true);
		$criteria->compare('t.ik_payment_state',$this->ik_payment_state,true);
		$criteria->compare('t.ik_trans_id',$this->ik_trans_id);
		$criteria->compare('t.ik_currency_exch',$this->ik_currency_exch,true);
		$criteria->compare('t.ik_fees_payer',$this->ik_fees_payer,true);
		$criteria->compare('t.ik_sign_hash',$this->ik_sign_hash,true);

        return parent::searchInit($criteria);
    }
}