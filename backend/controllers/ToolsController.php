<?php
class ToolsController extends BackController
{
		
	
	public function actionAdmin()
		{
			// save token to session for proper working of flash uploader
			app()->session['csrfToken'] = Yii::app()->request->csrfToken;
				 parent::actionAdmin();
	}
	
}