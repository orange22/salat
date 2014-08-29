<?php
class CourseController extends BackController
{
	protected function afterActionDone($model)
    {


        if(!isset($_POST['Seo']))
        if(isset($_POST['CourseIngredient']))
        {
            $hasErrors = false;
            $psModel = new CourseIngredient();
            $CourseIngredientData = $_POST['CourseIngredient'];
            foreach($CourseIngredientData as $idx => $item)
            {
                $psModel->ingredient_id = $item['ingredient_id'];
                $psModel->value = $item['value'];

                if(!$psModel->validate(array('ingredient_id', 'value', )))
                {
                    $hasErrors = true;
                    user()->addFlash(
                        'error',
                        $this->renderPartial('//inc/_model_errors', array('data' => $psModel->stringifyAttributeErrors()), true)
                    );
                    continue;
                }
               /*
                if(!(int)$item['ingredient_id'] && !(float)$item['value'] )
                                   unset($CourseIngredientData[$idx]);*/
               
            }

          	 if(!$hasErrors)
                CourseIngredient::model()->updateForCourse($model->id, $CourseIngredientData); 
        }else{
        	 CourseIngredient::model()->updateForCourse($model->id, array());
        }

        return parent::afterActionDone($model);
    }
}