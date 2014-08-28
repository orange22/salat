<?php
/**
 * This is the model class for table "{{file}}".
 *
 * The followings are the available columns in table '{{file}}':
 *
 * @property integer $id
 * @property string $file
 * @property string $path
 * @property integer $width
 * @property integer $height
 * @property integer $size
 *
 * The followings are the available model relations:
 * @property News[] $news
 * @property Product[] $productThumbs
 * @property Product[] $productsImages
 * @property ProductImage[] $productImageThumbs
 * @property ProductImage[] $productImageImages
 * @property SizeChart[] $sizeCharts
 * @property Slide[] $slides
 */
class File extends CActiveRecord
{
    /**
     * Temporary file name for proper validation
     *
     * @var string
     */
    protected $tmpFile = null;

    /**
     * Get file as image
     *
     * @param string $alt Image alt
     * @param array $htmlOptions Additional options
     * @return string HTML image tag
     */
    public function asHtmlImage($alt = '', $htmlOptions = array())
    {
        if(!($url = self::url($this)))
            return '';

        if(!isset($htmlOptions['width']))
            $htmlOptions['width'] = $this->width;
        if(!isset($htmlOptions['height']))
            $htmlOptions['height'] = $this->height;

        return CHtml::image($url, $alt, $htmlOptions);
    }

    /**
     * Get file as URL
     *
     * @return string
     */
    public function asUrl()
    {
        return $this->getFileUrl();
    }

    /**
     * Base path to upload dir
     *
     * @static
     * @param mixed $data Array with [file, path] keys or Object with {file, path} attributes
     * @return string Path to upload parent dir or path to file if $data provided
     */
    public static function basePath($data = null)
    {
        $path = array(
            Yii::app()->basePath,
            '..',
            (isset(Yii::app()->params['webRoot']) ? Yii::app()->params['webRoot'] : '')
        );

        if($data && isset($data['path']) && isset($data['file']))
        {
            $path[] = $data['path'];
            $path[] = $data['file'];
        }

        return rtrim(implode(DS, array_filter($path)), DS.' ');
    }

    /**
     * Calculate file usages
     *
     * @return array
     */
    public function calcUsages()
    {
        $o = 0;
        $sql = Yii::app()->db->createCommand();
        foreach($this->relations() as $params)
        {
            /** @var $model FileAttachBehavior */
            $model = call_user_func(array($params[1], 'model'));

            $sql->select('COUNT(*)');
            $sql->from($model->tableName());

            $where = array(
                array('and', "{$params[2]} = :id"),
                array(':id' => $this->id)
            );
            if($model->hasAttribute('pid'))
            {
                $where[0][] = "language_id = :lang";
                $where[1][':lang'] = Language::getDefault();
            }

            if(isset($params['condition']))
            {
                $where[0][] = $params['condition'];
            }
            if(isset($params['params']))
            {
                $where[1] = CMap::mergeArray($where[1], $params['params']);
            }

            $sql->where($where[0], $where[1]);

            $o += $sql->queryScalar();
            $sql->reset();
        }

        return $o;
    }

    /**
     * Delete all unused registered files
     */
    public function deleteAllUnused()
    {
        $used = $unused = array();

        self::model()->deleteAllByAttributes(array('file' => ''));

        $sql = Yii::app()->db->createCommand();
        $files = $sql->select('id')->from('{{file}}')->queryColumn();

        $sql->reset();
        // prepare array of all used files
        foreach($this->relations() as $params)
        {
            /** @var $model BaseActiveRecord */
            $model = call_user_func(array($params[1], 'model'));

            $where = array(0 => '', 1 => array());
            if(isset($params['condition']))
            {
                $where[0] = $params['condition'];
            }
            if(isset($params['params']))
            {
                $where[1] = $params['params'];
            }

            $sql->select($params[2])
                ->from($model->tableName());

            // relation condition
            if($where[0])
            {
                $sql->where($where[0], $where[1]);
            }

            $buff = $sql->queryColumn();
            $buff = array_unique(array_filter($buff));
            $used = array_merge($used, $buff);

            $sql->reset();
        }

        $unused = array_diff($files, array_unique($used));

        $sql->reset();
        $items = $sql->select('file, path')
            ->from('{{file}}')
            ->where(array('in', 'id', $unused))
            ->queryAll();
        foreach($items as $item)
        {
            @unlink(self::basePath($item));
        }

        self::model()->deleteAllByAttributes(array('id' => array_values($unused)));
    }

    /**
     * Delete file from FS
     */
    public function deleteRealFile()
    {
        @unlink($this->getFilePath());
    }

    /**
     * Delete unused file
     */
    public function deleteUnused()
    {
        $unused = true;
        $sql = Yii::app()->db->createCommand();
        foreach($this->relations() as $params)
        {
            /** @var $model BaseActiveRecord */
            $model = call_user_func(array($params[1], 'model'));
            $i18n = $model instanceof LangActiveRecord;

            $subSql = sprintf('(SELECT `id` FROM `%s` WHERE `%s` = %s %s) t',
                $model->tableName(),
                $params[2],
                $this->id,
                ($i18n ? 'GROUP BY pid' : '')
            );
            $sql->select('COUNT(*)');
            $sql->from($subSql);

            $count = $sql->queryScalar();

            $sql->reset();
            if($count > 1)
            {
                $unused = false;
            }
        }

        if($unused)
        {
            $this->delete();
        }
    }

    /**
     * Get image URL
     *
     * @return string
     */
    public function getFileUrl()
    {
        return Yii::app()->params['siteUrl']."/{$this->path}/{$this->file}";
    }

    /**
     * Get image URL from array
     *
     * @static
     * @param array $data Image data
     * @param string $pref Image data key prefix
     * @return string
     */
    public static function fileUrl($data, $pref = '')
    {
        $path = $data[$pref.'path'];
        $file = $data[$pref.'file'];

        return Yii::app()->params['siteUrl']."/{$path}/{$file}";
    }

    /**
     * Format file size
     *
     * @return string
     */
    public function formatSize()
    {
        return app()->format->formatSize($this->size);
    }

    /**
     * Get allowed extensions for upload
     *
     * @static
     * @param mixed $types File types (all,image,file,video)
     * @return array
     */
    public static function getAllowedExtensions($types = 'image')
    {
        $data = array(
            'image' => array('jpg', 'jpeg', 'gif', 'png'),
            'video' => array('swf', 'flv', 'mp4', 'avi', 'mpeg', 'mkv', 'ts'),
            'audio' => array('mp3', 'ogg', 'flac', 'wav'),
            'graphic' => array('psd', 'eps', 'ai', 'cdr'),
            'file' => array(
                'zip', 'rar', 'gzip', 'bzip', '7z',
                'pdf', 'doc', 'docx', 'txt', 'rtf',
            ),
        );

        if(!$types)
            return $data;

        $types = (array)$types;

        if(in_array('all', $types))
            return call_user_func_array('array_merge', $data);

        if(!empty($types))
        {
            $o = array();
            foreach($types as $type)
            {
                if(!$types || !isset($data[$type]))
                    continue;

                $o = array_merge($o, $data[$type]);
            }

            return $o;
        }

        return $data;
    }

    /**
     * @return string the file extension name for {@link name}.
     * The extension name does not include the dot character. An empty string
     * is returned if {@link name} does not have an extension name.
     */
    public function getExtensionName()
    {
        if(($pos = strrpos($this->file, '.')) !== false)
        {
            return (string)substr($this->file, $pos + 1);
        }

        return '';
    }

    /**
     * Get file extension
     *
     * @static
     * @param array|object $data Array or object with {file} attr
     * @param string $pref Prefix for multiple files
     * @return string Extension name
     */
    public static function extensionName($data, $pref = '')
    {
        if(($pos = strrpos($data[$pref.'file'], '.')) !== false)
            return (string)substr($data[$pref.'file'], $pos + 1);

        return '';
    }

    /**
     * Get maximum file size upload allowed
     *
     * @param int $default Default size (in Mb)
     * @return int
     */
    public static function getMaxUploadSize($default = 2)
    {
        $maxUpload = (int)(ini_get('upload_max_filesize'));
        $maxPost = (int)(ini_get('post_max_size'));
        $memoryLimit = (int)(ini_get('memory_limit'));
        $upload = min($maxUpload, $maxPost, $memoryLimit);
        $upload = !$upload ? $default : $upload;

        return floor($upload);
    }

    /**
     * Get image path
     *
     * @return string
     */
    public function getFilePath()
    {
        return self::basePath($this);
    }

    /**
     * Get file link tag
     *
     * @param File $obj
     * @param string $title
     * @return string HTML link tag
     */
    public static function htmlLinkFile($obj, $title = null)
    {
        if(!($url = self::url($obj)))
            return '';

        if(!$title)
            $title = Yii::t('cp', 'Download ({filename})', array('{filename}' => $obj->file));

        return CHtml::link($title, $url);
    }

    /**
     * Get link to file
     *
     * @static
     * @param mixed $data Array or object with {file, path} keys
     * @param string $pref Prefix for multiple files
     * @param string $title Link title
     * @return string HTML <a> tag
     */
    public static function htmlLinkFileEx($data, $pref = '', $title)
    {
        if(is_object($data))
            return self::htmlLinkFile($data, $title);

        if(!$data[$pref.'file'])
            return '';

        return CHtml::link($title, self::fileUrl($data, $pref));
    }

    /**
     * Get image tag
     *
     * @param File   $obj         File object
     * @param string $alt         Image alt
     * @param array  $htmlOptions Image html options
     * @return string HTML img tag
     */
    public static function htmlImage($obj, $alt = '', $htmlOptions = array())
    {
        if(!($url = self::url($obj)))
            return '';

        if(!isset($htmlOptions['width']))
            $htmlOptions['width'] = $obj->width;
        if(!isset($htmlOptions['height']))
            $htmlOptions['height'] = $obj->height;

        return CHtml::image($url, $alt, $htmlOptions);
    }

    /**
     * Get link to image with self image inside link
     *
     * @param File $obj
     * @param string $alt
     * @param array $imageHtmlOptions
     * @param array $linkHtmlOptions
     * @return string HTML <a><img/></a>
     */
    public static function htmlLinkImage($obj, $alt = '', $imageHtmlOptions = array(), $linkHtmlOptions = array())
    {
        if(!$img = self::htmlImage($obj, $alt, $imageHtmlOptions))
            return '';

        return CHtml::link($img, self::url($obj), $linkHtmlOptions);
    }

    /**
     * Try get file url
     *
     * @param File $obj
     * @return mixed File url or false
     */
    public static function url($obj)
    {
        if(!$obj || !($obj instanceof self))
            return false;

        return $obj->getFileUrl();
    }

    /**
     * Get image
     *
     * @static
     * @param mixed $data Image data. Array with params or File object
     * @param string $pref Image data key prefix
     * @param string $alt Image alt
     * @param array $htmlOptions Image additional options
     * @return string
     */
    public static function htmlImageEx($data, $pref = '', $alt = '', $htmlOptions = array())
    {
        if(is_object($data))
            return self::htmlImage($data, $alt, $htmlOptions);

        if(!$data[$pref.'file'])
            return '';

        if(!isset($htmlOptions['width']))
            $htmlOptions['width'] = $data[$pref.'width'];
        if(!isset($htmlOptions['height']))
            $htmlOptions['height'] = $data[$pref.'height'];

        return CHtml::image(self::fileUrl($data, $pref), $alt, $htmlOptions);
    }

    /**
     * Is file is image
     *
     * @return bool
     */
    public function getIsImage()
    {
        $src = is_object($this->file) ? $this->file : $this;

        return in_array(strtolower($src->getExtensionName()), self::getAllowedExtensions());
    }

    /**
     * Is file is image
     *
     * @static
     * @param array|object $data Array or object with {file} attribute
     * @param string $pref Prefix for multiple files
     * @return bool
     */
    public static function isImage($data, $pref = '')
    {
        if(is_object($data) && $data instanceof File)
            return $data->getIsImage();

        return in_array(strtolower(self::extensionName($data, $pref)), self::getAllowedExtensions());
    }

    /**
     * Returns the static model of the specified AR class.
     *
     * @param string $className
     * @return File the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{file}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('width, height, size', 'numerical', 'integerOnly' => true),
            array('file', 'length', 'max' => 45),
            array('path', 'length', 'max' => 64),

            array('id, file, path, width, height, size', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            /*
            'news' => array(self::HAS_MANY, 'News', 'thumb_id'),
                        'productThumbs' => array(self::HAS_MANY, 'Product', 'thumb_id'),
                        'productImages' => array(self::HAS_MANY, 'Product', 'image_id'),
                        'productImageThumbs' => array(self::HAS_MANY, 'ProductImage', 'thumb_id'),
                        'productImageImages' => array(self::HAS_MANY, 'ProductImage', 'image_id'),
                        'sizeCharts' => array(self::HAS_MANY, 'SizeChart', 'image_id'),
                        'slides' => array(self::HAS_MANY, 'Slide', 'image_id'),*/
            'dishtype' => array(self::HAS_MANY, 'Dishtype', 'image_id'),
            'dishthumb' => array(self::HAS_MANY, 'DishImage', 'thumb_id'),
            'dishimage' => array(self::HAS_MANY, 'DishImage', 'image_id'),
            
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'file' => Yii::t('backend', 'File'),
            'path' => Yii::t('backend', 'Path'),
            'width' => Yii::t('backend', 'Width'),
            'height' => Yii::t('backend', 'Height'),
            'size' => Yii::t('backend', 'Size'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.file', $this->file, true);
        $criteria->compare('t.path', $this->path, true);
        $criteria->compare('t.width', $this->width);
        $criteria->compare('t.height', $this->height);
        $criteria->compare('t.size', $this->size, true);

        return new CActiveDataProvider($this, array(
            'pagination' => array(
                'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),
            ),
            'criteria' => $criteria,
        ));
    }

    /**
     * Delete file from FS
     */
    protected function afterDelete()
    {
        parent::afterDelete();

        $this->deleteRealFile();
    }
}