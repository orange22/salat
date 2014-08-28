<?php
/**
 * Uploader class
 * Upload user files with some filters applying
 * Bundled filters: postUpload, copyFrom, resize
 *
 * @uses CImageHandler
 * @uses File
 * @uses Option
 */
class Uploader extends CApplicationComponent
{
    /**
     * Put files in sub folders
     * 0 - no sub folders
     * Max is 6 level deep
     * Greater value means deeper folders tree
     * File name MD5 pairs used as sub folders name
     *
     * @var int
     */
    protected $_subdirs = 0;

    /**
     * Processing attribute name
     *
     * @var string
     */
    protected $_attribute = '';

    /**
     * Errors
     *
     * @var array
     */
    protected $_errors = array();

    /**
     * File name
     *
     * @var string
     */
    protected $_file = '';

    /**
     * Original(cleaned) file name
     *
     * @var string
     */
    protected $_fileOrig = '';

    /**
     * Attribute filters
     *
     * @var array
     */
    protected $_filters = array(
        'pre' => array(),
        'filters' => array(),
    );

    /**
     * Attached model
     *
     * @var CActiveRecord
     */
    protected $_model = null;

    /**
     * File type
     *
     * @var string
     */
    protected $_type = false;

    /**
     * Constructor
     *
     * @param CModel $model
     */
    public function __construct($model = null)
    {
        if($model)
            $this->setModel($model);
    }

    /**
     * Adds a new error to the specified attribute.
     *
     * @param string $error new error message
     * @return void
     */
    public function addError($error)
    {
        $this->_errors[] = $error;
    }

    /**
     * Base path
     *
     * @return string
     */
    public static function getBasePath()
    {
        return File::basePath();
    }

    /**
     * Returns the errors for attribute.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * Get file extension name
     *
     * @static
     * @param string $file File name
     * @return string the file extension name.
     */
    public static function getExtensionName($file)
    {
        if(($pos = strrpos($file, '.')) !== false)
            return (string)substr($file, $pos + 1);

        return '';
    }

    /**
     * File type
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Get model upload path
     *
     * @param bool $absolute Return absolute path else relative
     * @return string
     */
    public function getUploadPath($absolute = true)
    {
        $o = array();
        if($absolute)
            $o[] = self::getBasePath();

        $o[] = Yii::app()->params['uploadUrl'];

        if(isset(Yii::app()->params['imagesDir']))
            $o[] = Yii::app()->params['imagesDir'];

        $o[] = strtolower(get_class($this->_model));
        $o[] = $this->subDirs($this->_fileOrig, DS);
        $o = trim(implode(DS, array_filter($o)), DS.' ');

        if($absolute)
            $o = DS.$o;

        return $o;
    }

    /**
     * Get file upload url
     *
     * @return string
     */
    public function getUploadUrl()
    {
        $o = array();
        $o[] = Yii::app()->params['uploadUrl'];

        if(isset(Yii::app()->params['imagesDir']))
            $o[] = Yii::app()->params['imagesDir'];

        $o[] = strtolower(get_class($this->_model));
        $o[] = $this->subDirs($this->_fileOrig);
        $o = trim(implode('/', array_filter($o)), '/ ');

        return $o;
    }

    /**
     * Clean file name
     *
     * @static
     * @param string $data File name
     * @return string
     */
    public static function cleanFileName($data)
    {
        $parts = explode('.', trim($data));
        $parts[0] = substr($parts[0], 0, 16);

        $name = preg_replace('/[^a-zA-Z0-9_-]/i', '', $parts[0]);
        $ext = preg_replace('/[^a-zA-Z0-9]/i', '', end($parts));

        $ext = !$ext ? pathinfo($data, PATHINFO_EXTENSION) : $ext;
        $name = !$name ? md5($data.uniqid(mt_rand(), true).microtime(true)) : $name;

        return $name.'.'.$ext;
    }

    /**
     * Clean path
     * Remove .,/
     *
     * @static
     * @param string $data Path
     * @return string
     */
    public static function cleanPath($data)
    {
        $data = trim(preg_replace('#[^a-zA-Z0-9_/-]#i', '', $data), '/ ');

        return $data;
    }

    /**
     * Prepare destination folder (create if needed) and check file does not exists.
     * Randomize file name if file exits.
     * MD5 hash as last measure, if previous failed
     *
     * @static
     * @param string $file Destination file name
     * @param string $dir Destination dir (relative to webRoot)
     * @return string New file name
     */
    public static function prepareDestination($file, $dir)
    {
        $dirPath = self::getBasePath().DS.$dir;
        if(!file_exists($dirPath))
            mkdir($dirPath, 0755, true);

        $i = 0;
        $file = strtolower($file);
        $fileParts = explode('.', $file);
        while(file_exists("{$dirPath}/{$file}")
            || File::model()->countByAttributes(array(
                'file' => $file,
                'path' => $dir,
            ))
        )
        {
            if(strlen($fileParts[0]) > 30)
                $fileParts[0] = substr($fileParts[0], 0, 15);

            $fileParts[0] .= mt_rand(100, 9999);

            $file = implode('.', $fileParts);
            if($i > 5)
            {
                $fileParts[0] = md5($fileParts[0].uniqid(mt_rand(), true).microtime(true));
                $file = implode('.', $fileParts);

                break;
            }
            ++$i;
        }

        return $file;
    }

    /**
     * Set attributes
     *
     * @param string $_attribute
     */
    public function setAttribute($_attribute)
    {
        $this->_attribute = $_attribute;
    }

    /**
     * Set model
     *
     * @param CModel $_model
     */
    public function setModel($_model)
    {
        $this->_model = $_model;
    }

    /**
     * Set file type
     *
     * @param string $_type
     */
    public function setType($_type)
    {
        $this->_type = $_type;
    }

    /**
     * Upload file associated with attribute
     *
     * @param string $attribute Attribute name
     * @throws CException
     * @return int|bool File ID or false if no file
     */
    public function upload($attribute)
    {
        $this->_file = null;
        $this->setAttribute($attribute);
        $this->_filters = $this->parseFilters();

        $this->applyFilters('pre');

        if($this->_file)
            $this->applyFilters();

        if(!$this->_file)
            return null;

        $file = new File();
        $file->setAttributes(array(
            'file' => $this->_file,
            'size' => filesize($this->getUploadPath().'/'.$this->_file),
            'path' => $this->getUploadUrl(),
        ));
        $file->setAttributes($this->getImageWH($this->getUploadPath().'/'.$this->_file));

        if(!$file->save())
        {
            //print_r($file->getErrors());
            $file->deleteRealFile();
            throw new CException('Failed saving file');
        }

        return $file->getPrimaryKey();
    }

    /**
     * Set subdirs number
     *
     * @param int $subdirs Number of sub directories
     */
    public function setSubdirs($subdirs)
    {
        $this->_subdirs = min($subdirs, 6);
    }

    /**
     * Apply filters to file
     *
     * @param string $type Filter type to apply
     * @return Uploader
     */
    protected function applyFilters($type = 'filters')
    {
        foreach($this->_filters[$type] as $func => $args)
        {
            $this->{"filter{$func}"}($args);
        }

        return $this;
    }

    /**
     * Get image file dimensions
     *
     * @param string File path
     * @return array [width, height]
     */
    protected function getImageWH($path)
    {
        if($this->_type != 'image')
            return array('width' => 0, 'height' => 0);

        $info = @getimagesize($path);
        if(isset($info[0]) && isset($info[1]))
            return array('width' => $info[0], 'height' => $info[1]);

        return array('width' => 0, 'height' => 0);
    }

    /**
     * Get file type
     *
     * @param string $ext
     * @return string
     */
    protected function fetchType($ext)
    {
        $ext = strtolower($ext);
        foreach(File::getAllowedExtensions(false) as $type => $extensions)
        {
            if(in_array($ext, $extensions))
                return $type;
        }

        return false;
    }

    /**
     * Parse filters
     *
     * @return array
     */
    protected function parseFilters()
    {
        $o = array(
            'pre' => array('postUpload' => array()),
            'filters' => array(),
        );

        // look for gallery specific options
        if(isset($this->_model->gallery))
        {
            $filters = (array)Option::getOpt(
                'image.'.strtolower(get_class($this->_model)).'.'.$this->_model->gallery->type.".{$this->_attribute}"
            );
        }
        else
        {
            $filters = (array)Option::getOpt('image.'.strtolower(get_class($this->_model)).'.'.$this->_attribute);
        }

        foreach($filters as $func => $filter)
        {
            $args = explode(',', $filter);
            if($func{0} == '^')
            {
                $func = substr($func, 1);
                $o['pre'][$func] = $args;

                continue;
            }

            $o['filters'][$func] = $args;
        }

        return $o;
    }

    /**
     * Extract sub directories path
     *
     * @param string $file File name
     * @param string $sep Directory separator
     * @return string
     */
    protected function subDirs($file, $sep = '/')
    {
        if($this->_subdirs < 1 || !$file)
            return '';

        $o = array();
        $hash = md5($file);
        for($i = 0; $i < $this->_subdirs; $i++)
            $o[] = substr($hash, $i * 2, 2);

        return implode($sep, $o);
    }

    /**
     * Copy attribute value from other attribute
     * + support multiple source attributes
     *
     * @param array $params
     * @return Uploader
     */
    public function filterCopyFrom($params = array())
    {
        // just POST upload
        $attrValue = $this->_model->{$this->_attribute};
        if(is_object($attrValue) && ($attrValue instanceof CUploadedFile))
        {
            return $this;
        }

        $srcAttr = null;
        // source attribute currently uploading so we can copy from new image, not old one
        while(!empty($params))
        {
            $srcAttr = array_shift($params);
            if(!empty($_FILES[get_class($this->_model)]['name'][$srcAttr]))
                break;

            $srcAttr = null;
        }

        if(!$srcAttr)
            return $this;

        /** @var $file File */
        if(!($file = File::model()->findByPk($this->_model->$srcAttr)))
            return $this;

        $this->fetchType(self::getExtensionName($file->file));
        $this->_fileOrig = $file->file;

        $relPath = $this->getUploadPath(false);
        $this->_file = self::prepareDestination(
            self::cleanFileName($file->file),
            $relPath
        );

        copy(
            self::getBasePath().'/'.$file->path.'/'.$file->file,
            self::getBasePath()."/{$relPath}/{$this->_file}"
        );

        return $this;
    }

    /**
     * Post uploading
     *
     * @return Uploader
     * @throws CException
     */
    public function filterPostUpload()
    {
        /** @var $attrValue CUploadedFile */
        $attrValue = $this->_model->{$this->_attribute};
        if(!is_object($attrValue) || !($attrValue instanceof CUploadedFile))
            return $this;

        $this->_type = $this->fetchType($attrValue->getExtensionName());
        // prepare file name before destination path calculation
        $this->_fileOrig = self::cleanFileName($attrValue->getName());

        $relPath = $this->getUploadPath(false);
        $this->_file = self::prepareDestination(
            $this->_fileOrig,
            $relPath
        );

        $filePath = self::getBasePath()."/{$relPath}/{$this->_file}";
        //echo $filePath;
        if(!$attrValue->saveAs($filePath))
            throw new CException('Failed uploading2 file');

        return $this;
    }

    /**
     * Image size filter
     *
     * @param array $params
     * @return mixed File path or false
     */
    public function filterSize($params = array())
    {
        if($this->_type != 'image')
            return $this;

        /** @var $ih CImageHandler */
        $ih = Yii::app()->ih;
        if(!$ih->load($this->getUploadPath().'/'.$this->_file))
        {
            return $this;
        }

        $sizing = $w = $h = false;
        list($w, $h, $sizing) = $params;
        $sizing = $sizing == 'crop' ? $sizing : 'resize';
        $w = $w === '' ? false : $w;
        $h = $h === '' ? false : $h;

        if($sizing == 'crop' && $w !== false && $h !== false)
        {
            $sizing = 'adaptiveThumb';
        }

        try
        {
            $ih->$sizing($w, $h);
            $ih->save($this->getUploadPath().'/'.$this->_file, false, 100);
        }
        catch(Exception $e)
        {
        }

        return $this;
    }
}