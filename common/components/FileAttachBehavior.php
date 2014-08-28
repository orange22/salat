<?php
/**
 * File handling behavior
 *
 * @property BaseActiveRecord $owner
 */
class FileAttachBehavior extends CActiveRecordBehavior
{
    /**
     * Model file attributes
     *
     * @var array
     */
    public $fileAttributes = array();

    /**
     * Model image attributes
     *
     * @var array
     */
    public $imageAttributes = array();

    /**
     * Delete related upload object
     *
     * @var bool
     */
    public $forceFileClean = false;

    /**
     * Clean model image/files attributes
     */
    public function cleanFiles()
    {
        foreach($this->uploadAttributes() as $attr)
        {
            /** @var $file File */
            if($file = File::model()->findByPk($this->owner->getAttribute($attr)))
            {
                $this->owner->setAttribute($attr, null);
                $file->deleteUnused();
            }
        }
    }

    /**
     * Copy errors to other model
     *
     * @param CModel $target
     * @return CModel
     */
    public function copyErrors($target)
    {
        foreach($this->owner->getErrors() as $attr => $errors)
        {
            $target->clearErrors($attr);
            $target->addError($attr, reset($errors));
        }

        return $this->owner;
    }

    /**
     * Check if appropriate _POST var is set and remove file
     *
     * @param array $attributes Attributes to check
     * @param string $multi Language
     * @return FileAttachBehavior
     */
    public function maybeRemoveUploaded($attributes, $multi = null)
    {
        foreach($attributes as $attr)
        {
            $key = 'del_'.get_class($this->owner);
            if($multi)
                $key .= '_'.$multi;
            $key .= '_'.$attr;

            if(!isset($_POST[$key]))
                continue;

            /** @var $file File */
            if($file = File::model()->findByPk($this->owner->getAttribute($attr)))
                $file->deleteUnused();
            $this->owner->setAttribute($attr, null);
        }

        return $this;
    }

    /**
     * Upload attributes files/images
     * Remove files if new uploading or checked to delete
     * Save file to DB and return file ID's
     *
     * @param array $data Outer files ID data
     * @param string $multi Multiple uploads per attribute (e.g. per language)
     * @return array Attributes files ID
     */
    public function saveUploads($data = array(), $multi = null)
    {
        if($multi)
            $fixed = $this->owner->fixedAttributes();
        $validateAttributes = array();
        foreach($this->uploadAttributes() as $attr)
        {
            if($multi && isset($fixed) && in_array($attr, $fixed))
                continue;

            // skip attribute if not found in $data (i.e. we do not have this field at all)
            if($multi && (!array_key_exists($multi, $data) || !array_key_exists($attr, $data[$multi])))
                continue;
            if(!$multi && !array_key_exists($attr, $data))
                continue;

            // set attribute value from outer data (_POST) if uploaded earlier
            if($multi)
                $this->owner->$attr = isset($data[$multi][$attr]) && $data[$multi][$attr] > 0 ? $data[$multi][$attr] : null;
            else
                $this->owner->$attr = isset($data[$attr]) && $data[$attr] > 0 ? $data[$attr] : null;

            $this->maybeRemoveUploaded(array($attr), $multi);

            $attrName = $multi ? "[{$multi}]{$attr}" : $attr;
            $upload = CUploadedFile::getInstance($this->owner, $attrName);

            // skip if not uploading and has value
            if(!$upload && $this->owner->$attr)
                continue;

            $this->owner->$attr = $upload;
            $validateAttributes[] = $attr;
        }
        $this->owner->validate($validateAttributes);

        return $this->owner->getAttributes($validateAttributes);
    }

    /**
     * Upload and save file/image
     * Multiple files uploaded as 1 file per request (i.e. Uploadify)
     *
     * @return array Assoc array of attributes values
     */
    public function saveSimpleUpload()
    {
        $validateAttributes = array();
        foreach($this->uploadAttributes() as $attr)
        {
            $upload = CUploadedFile::getInstance($this->owner, $attr);
            // skip if not uploading and has value
            if(!$upload && $this->owner->$attr)
                continue;

            $this->owner->$attr = $upload;
            $validateAttributes[] = $attr;
        }
        $this->owner->validate($validateAttributes);

        return $this->owner->getAttributes($this->uploadAttributes());
    }

    /**
     * All upload attributes (images + files)
     *
     * @return array
     */
    public function uploadAttributes()
    {
        return array_merge(
            $this->imageAttributes,
            $this->fileAttributes
        );
    }

    /**
     * Upload image/file if validation passed
     *
     * @param CModelEvent $event
     */
    public function afterValidate($event)
    {
        if($this->owner->getScenario() == 'upload')
        {
            Yii::app()->uploader->setModel($this->owner);
            // register uploaded files as File and set ID to attribute
            foreach($this->uploadAttributes() as $attribute)
            {
                if($this->owner->hasErrors($attribute) || (is_numeric($this->owner->$attribute) && $this->owner->$attribute > 0))
                    continue;

                $this->owner->$attribute = Yii::app()->uploader->upload($attribute);
            }
        }

        parent::afterValidate($event);
    }

    /**
     * Set image/files attributes to null if not set for proper DB relation
     *
     * @param CModelEvent $event
     * @return bool
     */
    public function beforeSave($event)
    {
        foreach($this->uploadAttributes() as $attr)
        {
            if(!$this->owner->getAttribute($attr))
                $this->owner->setAttribute($attr, null);
        }

        return true;
    }

    /**
     * Get attach image size hint
     *
     * @return string
     */
    public function getSizeHint()
    {
        $o = array();
        foreach($this->imageAttributes as $attribute)
        {
            $optKey = 'image.'.$this->owner->classId(true);
            if($this->owner->hasAttribute('type') && isset($this->owner->type))
                $optKey .= '.'.$this->owner->type;

            $optKey .= '.'.$attribute;
            $hintData = (array)Option::getOpt($optKey);
            if(!isset($hintData['size']))
                return '';

            $buff = array();
            list($w, $h) = explode(',', $hintData['size']);
            if($w)
                $buff[] = Yii::t('backend', 'width: {width}px', array('{width}' => $w));

            if($h)
                $buff[] = Yii::t('backend', 'height: {height}px', array('{height}' => $h));

            if(count($this->imageAttributes) > 1)
                $o[] = $this->owner->getAttributeLabel($attribute).': '.implode(', ', $buff);
            else
                $o[] = implode(', ', $buff);
        }

        return implode('; ', $o);
    }

    public function beforeDelete($event)
    {
        if($this->forceFileClean)
            $this->deleteRelatedUpload();
    }

    /**
     * Delete related upload objects
     */
    protected function deleteRelatedUpload()
    {
        $uplAttrs = $this->uploadAttributes();
        foreach($this->owner->relations() as $rel => $params)
        {
            if($params[1] !== 'File' || !in_array($params[2], $uplAttrs))
                continue;

            if($this->owner->$rel)
                $this->owner->$rel->delete();
        }
    }
}
