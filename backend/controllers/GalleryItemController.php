<?php
class GalleryItemController extends BackController
{
    protected static $cache = array();

    /**
     * Post back navigation
     *
     * @param int $pid Post PID
     * @return mixed Array [url, name] or null if post not found
     */
    public function postBackNav($pid)
    {
        if(isset(self::$cache[$pid]))
            return self::$cache[$pid];

        if(($retUrl = request()->getParam('returnUrl')))
            return base64_decode($retUrl);

        /** @var $model Post */
        $model = Post::model()->language()->pid($pid)->find();
        if(!$model)
            return null;

        $ent = Entity::get($model->entity);
        self::$cache[$pid] = array($ent.'/update', 'pid' => $pid, '#' => 'gallery-upload');

        return self::$cache[$pid];
    }

    /**
     * Sort gallery images
     */
    public function actionSort()
    {
        if(isset($_POST[$this->getModelName()]))
        {
            $postId = $_POST[$this->getModelName()]['post_pid'];
            GalleryItem::model()->updateSorting($postId, $_POST[$this->getModelName()]['order']);
            $this->redirectAction();
        }

        $this->redirectAction(array('gallery/admin'));
    }

    public function filterCancelled($filterChain)
    {
        if(isset($_POST['cancel']))
        {
            $this->redirect($this->postBackNav($_POST['GalleryItem']['post_pid']));
        }

        $filterChain->run();
    }

    protected function redirectAction($data = null)
    {
        if(!$data)
        {
            $this->redirect($this->postBackNav($_POST['GalleryItem']['post_pid']));
        }

        parent::redirectAction($data);
    }
}