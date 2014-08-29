<?php
class DishController extends BackController
{
		
	
	protected function afterActionDone($model)
		{
	
			if(isset($_POST['DrinkDish']))
			{

                if(count($_POST['DrinkDish'])>0)
                {
                    $hasErrors = false;
                    $psModel = new DrinkDish();
                    $DrinkDishData = $_POST['DrinkDish'];
                    foreach($DrinkDishData as $idx => $item)
                    {
                        $psModel->drink_id = $item['drink_id'];
                                             if(!$psModel->validate(array('drink_id', )))
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
                        DrinkDish::model()->updateForDrink($model->id, $DrinkDishData);
                }else{
                    DrinkDish::model()->updateForDrink($model->id, array());
                }
			}
			if(isset($_POST['DishSimilar']))
			{

                if(count($_POST['DishSimilar'])>0)
                {
                    $hasErrors = false;
                    $psModel = new DishSimilar();
                    $DishSimilarData = $_POST['DishSimilar'];
                    foreach($DishSimilarData as $idx => $item)
                    {
                        $psModel->similar_id = $item['similar_id'];
                                             if(!$psModel->validate(array('similar_id', )))
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
                        DishSimilar::model()->updateForSimilar($model->id, $DishSimilarData);
                }else{
                    DishSimilar::model()->updateForSimilar($model->id, array());
                }
			}else
                DishSimilar::model()->deleteAllByAttributes(array('dish_id'=>$model->id));
            if(isset($_POST['DishTool']))
            {

                if(count($_POST['DishTool'])>0)
                {
                    $hasErrors = false;
                    $psModel = new DishTool();
                    $DishToolData = $_POST['DishTool'];
                    foreach($DishToolData as $idx => $item)
                    {
                        $psModel->tool_id = $item['tool_id'];
                        if(!$psModel->validate(array('tool_id', )))
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
                        DishTool::model()->updateForTool($model->id, $DishToolData);
                }else{
                    DishTool::model()->updateForTool($model->id, array());
                }
            }else
                DishTool::model()->deleteAllByAttributes(array('dish_id'=>$model->id));
			if(isset($_POST['Portion']))
	        {
                if(count($_POST['Portion'])>0)
                {
                    $hasErrors = false;
                    $psModel = new Portion();
                    $PortionData = $_POST['Portion'];
                    foreach($PortionData as $idx => $item)
                    {
                        if($item['value']>0){

                            $psModel->value = $item['value'];

                            if(!$psModel->validate(array('value', )))
                            {
                                $hasErrors = true;
                                user()->addFlash(
                                    'error',
                                    $this->renderPartial('//inc/_model_errors', array('data' => $psModel->stringifyAttributeErrors()), true)
                                );
                                continue;
                            }
                        }
                       /*
                        if(!(int)$item['dish_id'] && !(float)$item['value'] )
                                           unset($PortionData[$idx]);*/

                    }

                     if(!$hasErrors)
                        Portion::model()->updateForPortion($model->id, $PortionData);
                }else{
                Portion::model()->updateForPortion($model->id, array());
                }
	        }
				 return parent::afterActionDone($model);
		}

		
	
	public function actionAdmin()
		{
			// save token to session for proper working of flash uploader
			app()->session['csrfToken'] = Yii::app()->request->csrfToken;
				 parent::actionAdmin();
	}
	
}