<?php
/**
 * Generic layout part rendering
 *
 * @property FrontController $owner
 */
class LayoutBehavior extends CBehavior
{
    public function renderHeader()
    {
        $this->owner->renderPartial('//layouts/inc/_header', array(
            'menu' => PageFront::menu(),
            'social' => array_filter(array(
                'facebook' => Option::getOpt('site.facebook'),
                'twitter' => Option::getOpt('site.twitter'),
                'vk' => Option::getOpt('site.vkontakte'),
            ))
        ));
    }

    public function renderFooter()
    {
        $this->owner->renderPartial('//layouts/inc/_footer', array(
        ));
    }
}