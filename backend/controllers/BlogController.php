<?php
class BlogController extends BackController
{
    public function filters()
    {
        return array(
            'rights',
        );
    }


    protected function afterActionDone($model)
    {
        if(isset($_POST['BlogDish']))
        {
            if(count($_POST['BlogDish'])>0)
            {
                $hasErrors = false;
                $psModel = new BlogDish();
                $BlogDishData = $_POST['BlogDish'];
                foreach($BlogDishData as $idx => $item)
                {
                $psModel->dish_id = $item['dish_id'];
                    if(!$psModel->validate(array('dish_id', )))
                    {
                    $hasErrors = true;
                    user()->addFlash(
                    'error',
                    $this->renderPartial('//inc/_model_errors', array('data' => $psModel->stringifyAttributeErrors()), true)
                    );
                    continue;
                    }
                }
                if(!$hasErrors)
                    BlogDish::model()->updateForDish($model->id, $BlogDishData);
            }else{
                BlogDish::model()->updateForDish($model->id, array());
                //BlogDish::model()->deleteAllByAttributes(array('blog_id'=>$model->id));
            }
        }
        if(isset($_POST['BlogDish'][0]['dish_id']) && count($_POST['BlogDish'])==1 && $_POST['BlogDish'][0]['dish_id']<1)
            BlogDish::model()->deleteAllByAttributes(array('blog_id'=>$model->id));

        return parent::afterActionDone($model);
    }
}