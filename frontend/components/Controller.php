<?php
/**
 * Controller.php
 *
 * @author: antonio ramirez <antonio@clevertech.biz>
 * Date: 7/23/12
 * Time: 12:55 AM
 */
class Controller extends CController {

	public $breadcrumbs = array();
	public $menu = array();
	public function renderTopDishes(){
		/*
		$pages = new Pages;
				$Menu=$pages->getPages();
				foreach($Menu as $p)
					$menudata[$p->attributes['pid']][$p->attributes['id']]=$p->attributes;*/
		$dishes=array(1,2,3,4,5);
		return $this->renderPartial('/parts/dishes',array('dishes'=>$dishes),true);
	}
}
