<?php
Yii::import('ext.shopping-cart.IECartPosition');

/**
 * This is the model class for table "{{product}}".
 *
 * The followings are the available columns in table '{{product}}':
 * @property integer $id
 * @property integer $pid
 * @property string $language_id
 * @property integer $mini_id
 * @property integer $thumb_id
 * @property integer $image_id
 * @property integer $image2_id
 * @property integer $brand_id
 * @property string $gender
 * @property string $title
 * @property string $text
 * @property integer $sort
 * @property integer $status
 *
 * @method Product active
 * @method Product cache($duration = null, $dependency = null, $queryCount = 1)
 * @method Product indexed($column = 'language_id')
 * @method Product language($lang = null)
 * @method Product select($columns = '*')
 * @method Product limit($limit, $offset = 0)
 * @method Product sort($columns = '')
 *
 * The followings are the available model relations:
 * @property OrderProduct[] $orderProducts
 * @property Language $language
 * @property File $mini
 * @property File $thumb
 * @property File $image
 * @property File $image2
 * @property Brand $brand
 * @property ProductInfo[] $info
 * @property ProductInfo $exactInfo
 * @property Section[] $sections
 */
class Product implements IECartPosition
{
    /**
     * Sections filter
     *
     * @var int
     */
    public $sectionsFilter = null;

    /**
     * All sections grouped
     *
     * @var string
     */
    public $allSections = null;

    /**
     * Exact product info (size, quantity, price)
     *
     * @var ProductInfo
     */
    protected $exactInfo;

    /**
     * Is product is single (i.e. has no sizes)
     *
     * @return bool
     */
    public function getIsSingle()
    {
        return (!$this->info || $this->info[0]->size == 0);
    }

    /**
     * @throws CException
     * @return string id
     */
    public function getId()
    {
        if($this->exactInfo)
            return $this->id.':'.$this->exactInfo->id;

        throw new CException(Yii::t('theme', 'Product exact info not defined.'));
    }

    /**
     * @throws CException
     * @return float price
     */
    public function getPrice()
    {
        if($this->exactInfo)
            return $this->exactInfo->price;

        throw new CException(Yii::t('theme', 'Product exact info not defined.'));
    }

    /**
     * Fetch product with info
     *
     * @param array $infoData Assoc array of infoID => Product PID
     * @return Product[] Array of products with info attached
     */
    public function fetchCartItems($infoData)
    {
        /** @var $products Product[] */
        $products = self::model()->active()->indexed('id')->findAllByAttributes(array(
            'pid' => array_unique(array_values($infoData))
        ));

        /** @var $info ProductInfo[] */
        $info = ProductInfo::model()->findAllByAttributes(array('id' => array_keys($infoData)));

        $o = array();
        foreach($info as $item)
        {
            /** @var $model Product */
            $model = clone $products[$item->id];
            //$model->setExactInfo($item);
            $o[] = $model;
        }

        return $o;
    }

    /**
     * @return \ProductInfo
     */
    public function getExactInfo()
    {
        return $this->exactInfo;
    }

    /**
     * @param \ProductInfo $exactInfo
     */
    public function setExactInfo($exactInfo)
    {
        $this->exactInfo = $exactInfo;
    }

    /**
     * Product count excluding those, which quantity = 0
     *
     * @return int
     */
    public function totalCount()
    {
        return Product::model()
            ->cache()
            ->language()
            ->active()
            ->count('t.pid NOT IN (SELECT product_pid FROM {{products_info}} WHERE product_pid = t.pid AND total_qty = 0)');
    }

    /**
     * Fetch product with extended data
     *
     * @param int $pid Post PID
     * @return array
     */
    public function fetch($pid)
    {
        $sql = "
            SELECT
                *,
                SUM(pinf.quantity) AS total_qty,
	            GROUP_CONCAT(CONCAT_WS('#', pinf.id, pinf.size, pinf.price) ORDER BY pinf.size SEPARATOR ';') AS info,
                (SELECT pid FROM {{product}} p1 WHERE p1.status = 1 AND p1.language_id = t.language_id
                    AND p1.sort >= t.sort
                    AND p1.pid > t.pid
                    AND p1.pid NOT IN (SELECT product_pid FROM {{products_info}} WHERE product_pid = p1.pid AND total_qty = 0)
                ORDER BY sort, pid LIMIT 1) AS next,
                (SELECT pid FROM {{product}} p2 WHERE p2.status = 1 AND p2.language_id = t.language_id
                    AND p2.sort <= t.sort
                    AND p2.pid < t.pid
                    AND p2.pid NOT IN (SELECT product_pid FROM {{products_info}} WHERE product_pid = p2.pid AND total_qty = 0)
                ORDER BY sort DESC, pid DESC LIMIT 1) AS prev

            FROM (
                SELECT
                    p.pid,
                    p.title,
                    p.text,
                    p.thumb_id,
                    p.sort,
                    p.language_id,
                    @rownum := @rownum + 1 AS rownum
                FROM {{product}} p
                JOIN (SELECT @rownum := 0) vars
                WHERE p.status = 1
                    AND p.language_id = :lang
                    AND p.pid NOT IN (SELECT product_pid FROM {{products_info}} WHERE product_pid = p.pid AND total_qty = 0)
                ORDER BY p.sort DESC
            ) t
            LEFT JOIN {{product_info}} pinf ON pinf.product_pid = t.pid AND pinf.quantity > 0
            LEFT JOIN {{file}} f ON f.id = t.thumb_id
            WHERE t.pid = :pid
        ";

        $data = app()->db->createCommand($sql)->queryRow(true, array(
            ':pid' => $pid,
            ':lang' => app()->language,
        ));

        $isSingle = false;
        $info = array();
        foreach(explode(';', $data['info']) as $sizePrice)
        {
            list($id, $size, $price) = array_pad((array)explode('#', $sizePrice), 3, 0);
            $info[$id] = array('id' => $id, 'size' => $size, 'price' => $price);
            if($size == 0)
                $isSingle = true;
        }
        $data['info'] = $info;
        $data['isSingle'] = $isSingle;

        return $data;
    }

    /**
     * Get specified gender
     *
     * @param string $gender
     * @return string
     * @throws CException
     */
    public function getGender($gender)
    {
        $data = $this->getGenders();
        if(isset($data[$gender]))
            return $data[$gender];

        throw new CException(Yii::t('cp', 'Gender "{gender}" not defined.', array('{gender}' => $gender)));
    }

    /**
     * Get genders list
     *
     * @return array
     */
    public function getGenders()
    {
        return array(
            'f' => Yii::t('theme', 'For women'),
            'm' => Yii::t('theme', 'For men'),
            'u' => Yii::t('theme', 'For all'),
        );
    }

    /**
     * Get post block size
     *
     * @param int $w Image width
     * @param int $h Image height
     * @return string Block size 'x-x'
     */
    public function resolveSize($w = 0, $h = 0)
    {
        $w = !$w && $this->image ? $this->image->width : $w;
        $h = !$h && $this->image ? $this->image->height : $h;

        return Tool::resolveSize($w, $h);
    }

    public function fixedAttributes()
    {
        return CMap::mergeArray(parent::fixedAttributes(), array(
            'gender',
            'mini_id',
            'thumb_id',
            'image_id',
            'image2_id',

            'sections',
        ));
    }

    public function behaviors()
    {
        return array(
            'attach' => array(
                'class' => 'app.components.behaviors.FileAttachBehavior',
                'imageAttributes' => array(
                    'mini_id',
                    'thumb_id',
                    'image2_id',
                    'image_id',
                ),
                'fileAttributes' => array(
                ),
            ),
            'junction' => array(
                'class' => 'app.components.cp.JunctionBehavior',
                'relations' => array(
                    'sections' => array(
                        'table' => '{{product_section}}',
                        'idColumn' => 'pid',
                        'primaryColumn' => 'section_pid',
                        'secondaryColumn' => 'product_pid'
                    ),
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
            array('brand_id, sort, status', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 256),
            array('gender', 'in', 'range' => array('m', 'f', 'u')),
            array('mini_id, thumb_id, image_id, image2_id, text', 'safe'),
            array('mini_id, thumb_id, image_id, image2_id', 'file', 'types' => File::getAllowedExtensions(), 'allowEmpty' => true, 'on' => 'upload'),
            array('brand_id', 'exist', 'className' => 'Brand', 'attributeName' => 'id'),

            array('id, pid, language_id, mini_id, thumb_id, image_id, image2_id, brand_id, gender, title, text, sort, status, sectionsFilter', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'orderProducts' => array(self::HAS_MANY, 'OrderProduct', 'product_pid'),
            'language' => array(self::BELONGS_TO, 'Language', 'language_id'),
            'mini' => array(self::BELONGS_TO, 'File', 'mini_id'),
            'thumb' => array(self::BELONGS_TO, 'File', 'thumb_id'),
            'image' => array(self::BELONGS_TO, 'File', 'image_id'),
            'image2' => array(self::BELONGS_TO, 'File', 'image2_id'),
            'brand' => array(self::BELONGS_TO, 'Brand', 'brand_id'),
            'info' => array(self::HAS_MANY, 'ProductInfo', 'product_pid'),
            'sections' => array(self::MANY_MANY, 'Section', '{{product_section}}(product_pid, section_pid)', 'together' => true),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'pid' => 'PID',
            'language_id' => Yii::t('cp', 'Language'),
            'mini_id' => Yii::t('cp', 'Mini'),
            'thumb_id' => Yii::t('cp', 'Thumb'),
            'image_id' => Yii::t('cp', 'Image'),
            'image2_id' => Yii::t('cp', 'Image Grayscale'),
            'brand_id' => Yii::t('cp', 'Brand'),
            'gender' => Yii::t('cp', 'Gender'),
            'title' => Yii::t('cp', 'Title'),
            'text' => Yii::t('cp', 'Text'),
            'sort' => Yii::t('cp', 'Sort'),
            'status' => Yii::t('cp', 'Status'),

            'sectionsFilter' => Yii::t('cp', 'Categories')
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->with = array('brand', 'sections' => array('select' => false, 'scopes' => array('language')));
        $criteria->group = 't.pid';
        $criteria->together = true;

        $criteria->select = array(
            'GROUP_CONCAT(sections.title) AS allSections',
            't.*'
        );

        $criteria->compare('t.id',$this->id);
		$criteria->compare('t.pid',$this->pid);
		$criteria->compare('t.mini_id',$this->mini_id);
		$criteria->compare('t.thumb_id',$this->thumb_id);
		$criteria->compare('t.image_id',$this->image_id);
		$criteria->compare('t.brand_id',$this->brand_id);
		$criteria->compare('t.gender',$this->gender);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.text',$this->text,true);
		$criteria->compare('t.sort',$this->sort);
		$criteria->compare('t.status',$this->status);
        $criteria->compare('sections.pid', $this->sectionsFilter);

        return parent::searchInit($criteria);
    }
}