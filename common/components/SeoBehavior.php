<?php
/**
 * @property LangActiveRecord $owner
 */
class SeoBehavior extends CActiveRecordBehavior
{
    /**
     * Fetch owner model meta data
     *
     * @return mixed Array [title, keywords, description] or null
     */
    public function fetchMetaData()
    {
        return app()->db->createCommand()
            ->select('title, keywords, description')
            ->from('{{seo}}')
            ->where('pid = :pid AND entity = :entity')
            ->queryRow(true, array(
                ':pid' => $this->owner->id,
                ':entity' => $this->owner->classId(true),
            ));
    }

    public function afterSave($event)
    {
    	if(isset($_POST['Seo']))
        {
        	Seo::model()->saveMeta($_POST['Seo'], $this->owner);
            $this->owner->refreshCache();
        }

        return true;
    }

    public function afterDelete($event)
    {
        Seo::model()->deleteMeta($this->owner);
        $this->owner->refreshCache();

        return true;
    }
}