<?php

class RecipeController extends FrontController
{
    const PAGE_SIZE = 10000;
	public $dishtype_id;
	public function actionIndex($page=1)
	{
        $view='index';
        $total=count(Course::model()->findAll('t.recipe=1'));
			
		$Paging = new Paging('recipe/page',self::PAGE_SIZE, $total, $page);
		$topdishes=Course::model()->limit(self::PAGE_SIZE,$Paging->getStart())->findAll('t.recipe=1');
		$this->render($view,array(
		    'topdishes'=>$topdishes,
		    'pages'=>$Paging->GetHTML(),
		));
	}
	public function actionCategory($id,$page=1)
	{
        $this->dishtype_id=$id;
        if($id==18){
            $total=count(Dish::model()->with('dishtype')->active()->findAll('dishtype.dpid='.$id.''));
            $Paging = new Paging('product/category/'.$id.'/page',self::PAGE_SIZE, $total, $page);
            $topdishes=Dish::model()->with(array('dishImages','courses'=>array('with'=>array('coursetype'=>array('with'=>'coursetypeimage'))),'dishtype'=>array('with'=>'dishtypeimage')))->active()->sort('t.sort ASC')->limit(self::PAGE_SIZE,$Paging->getStart())->findAll('dishtype.dpid='.$id.'');
        }else{
            $total=count(Dish::model()->active()->findAll('t.dishtype_id='.$id.''));
            $Paging = new Paging('dish/category/'.$id.'/page',self::PAGE_SIZE, $total, $page);
            $topdishes=Dish::model()->with(array('dishImages','courses'=>array('with'=>array('coursetype'=>array('with'=>'coursetypeimage'))),'dishtype'=>array('with'=>'dishtypeimage')))->active()->sort('t.sort ASC')->limit(self::PAGE_SIZE,$Paging->getStart())->findAll('t.dishtype_id='.$id.'');
        }
		if($seo=Seo::model()->find('t.pid=:id AND t.entity="course"', array(':id'=>$id)))
		{
			$this->seo_title=$seo->title;
			$this->seo_description=$seo->description;
			$this->seo_keywords=$seo->keywords;
		}
        $view='index';
        if(isset($topdishes[0]->dishtype->dpid) || $id==18)
            if($topdishes[0]->dishtype->dpid==18 || $id==18)
                $view='products';
		$this->render($view,array(
		    'topdishes'=>$topdishes,
		    'pages'=>$Paging->GetHTML(),
		));
	}
	public function actionView2($id)
	{
		$order=null;
		if(yii::app()->user->getId()>0)	
		$order=OrderDish::model()->with(array('order'=>array('joinType'=>'INNER JOIN', 'on'=>'order.orderstate_id>2 AND order.user_id='.yii::app()->user->getId().'')))->find('t.dish_id=:id', array(':id'=>$id));
		$dish=Dish::model()->with(array('portions','cookware1','cookware2','dishImages','shef'=>array('with'=>array('signature')),'dishtype'=>array('with'=>'dishtypeimage')))->sort('portions.value ASC')->active()->find('t.id=:id', array(':id'=>$id));
		$shef=User::model()->findByPk($dish->shef_id);
        $this->dishtype_id=$dish->dishtype_id;
		if(isset($dish->dishImages[0]->image))
		$this->og_image=$dish->dishImages[0]->image->path.'/'.$dish->dishImages[0]->image->file;
		$this->og_title=$dish->title;
		$this->og_description=strip_tags($dish->detail_text);
		
		if($seo=Seo::model()->find('t.pid=:id AND t.entity="dish"', array(':id'=>$id)))
		{
			$this->seo_title=$seo->title;
			$this->seo_description=$seo->description;
			$this->seo_keywords=$seo->keywords;
		}
		
		
		$course=Course::model()->with(array('image','coursetype'=>array('with'=>'dishtypeimage'),'courseIngredients'=>array('with'=>array('ingredient'=>array('with'=>'image')))))->sort()->active()->findAll('t.dish_id=:id', array(':id'=>$id));
		$otherdishes=DishSimilar::model()->with(array('similar'=>array('with'=>'dishImages')))->limit(4,0)->findAll('t.dish_id='.$dish->id);
        $tools=DishTool::model()->with(array('tool'=>array('with'=>'dishImages')))->limit(4,0)->findAll('t.dish_id='.$dish->id);

		$steps=array();
		$videos=array();
		$this->render('view',array(
            'dish'=>$dish,
            'course'=>$course,
            'steps'=>$steps,
            'videos'=>$videos,
            'order'=>$order,
            'otherdishes'=>$otherdishes,
            'tools'=>$tools,
            'shef'=>$shef
        ));
	}
    public function actionView($id)
    {
        $course=Course::model()->findByPk($id);
        $videos=Video::model()->sort()->active()->findAll('t.course_id='.$id);
        if($course->dish_id)
            $dishes=DishSimilar::model()->with(array('similar'=>array('with'=>'dishImages')))->limit(4,0)->findAll('t.dish_id='.$course->dish_id.' AND similar.status=1');
        else
            $dishes=DishSimilar::model()->with(array('similar'=>array('with'=>'dishImages')))->limit(4,0)->findAll('similar.status=1');

        if($seo=Seo::model()->find('t.pid=:id AND t.entity="course"', array(':id'=>$id)))
        {
            $this->seo_title=$seo->title;
            $this->seo_description=$seo->description;
            $this->seo_keywords=$seo->keywords;
        }

        $this->render('view',array('course'=>$course,'videos'=>$videos,'dishes'=>$dishes));
    }
    public function actionPdf() {
        $id=$_GET['id'];
        $course=Course::model()->findByPk($id);
        if(isset($_GET['print'])){
            $printable=$_GET['print'];
        }else
            $printable=0;
            $this->renderPartial('recipe_pdf',array('course'=>$course,'printable'=>$printable));
        /*}else{
            $printable=0;
            $pdf = Yii::createComponent('common.extensions.tcpdf.ETcPdf',
                'P', 'cm', 'A4', true, 'UTF-8');
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor("Lpovar");
            $pdf->SetTitle("Товарный чек");
            $pdf->SetKeywords("TCPDF, PDF, example, test, guide");
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            //$pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->SetFont('freeserif', '', 11);
            $pdf->writeHTML($this->renderPartial('recipe_pdf',array('course'=>$course,'printable'=>$printable),true), true, false, false, false, '');
            $pdf->Output("example_002.pdf", "I");
        }*/
    }

}