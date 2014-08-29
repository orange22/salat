<?php

class BlogController extends FrontController
{
	public function actionIndex()
	{
        $likes = isset(Yii::app()->request->cookies['bloglikes']->value)?Yii::app()->request->cookies['bloglikes']->value:'';


        $items=Blog::model()->with('blogDishes')->sort('t.date_create DESC')->active()->findAll();
        $dishes=Dish::model()->with('dishtype')->findAll(array(
            "condition" => "t.status=1 AND dishtype.dpid is null",
            "order" => "rand()",
            "limit" => 3,
        )  );
		$this->render('index',array('items'=>$items,'dishes'=>$dishes));
	}
	
	
	public function actionView($id=null) {
		//$managers=User::model()->with(array('userUsertypes'=>array('joinType'=>'inner join')))->findAll('userUsertypes.id=3');
        $item=Blog::model()->with('blogDishes')->active()->findByPk($id);
        $item->views=$item->views+1;
        $connection=Yii::app()->db;
        $command=$connection->createCommand('UPDATE gs_blog SET gs_blog.`views`=gs_blog.`views`+1 WHERE id='.$id);
        $rowCount=$command->execute();

        //print_r($item2->attributes);
        /* $dishes=Dish::model()->with('dishtype')->findAll(array(
            "condition" => "t.status=1 AND dishtype.dpid is null",
            "order" => "rand()",
            "limit" => 3,
            "together" => true
        )  );*/
		if($seo=Seo::model()->find('t.pid=:id AND t.entity="'.$this->id.'"', array(':id'=>$item->id)))
		{
			$this->seo_title=$seo->title;
			$this->seo_description=$seo->description;
			$this->seo_keywords=$seo->keywords;
		}
		
		$this->render('view', array('item'=>$item));
	}
    public function actionLike() {


            if(isset($_POST['id'])){
                $likes = isset(Yii::app()->request->cookies['bloglikes']->value)?Yii::app()->request->cookies['bloglikes']->value:array();
                $blog=Blog::model()->findByPk($_POST['id']);
                if(count($likes)>0){
                   if(in_array($_POST['id'],$likes)){
                       if($blog->likes>0)
                       $blog->likes=$blog->likes-1;
                       if(($key = array_search($_POST['id'], $likes)) !== false) {
                           unset($likes[$key]);
                       }
                   }else{
                       $blog->likes=$blog->likes+1;
                       $likes[]=$_POST['id'];
                   }
                }else{
                    $blog->likes=$blog->likes+1;
                    $likes[]=$_POST['id'];
                }
                Yii::app()->request->cookies['bloglikes'] = new CHttpCookie('bloglikes', $likes);
                $blog->save();
                $this->sendJsonResponse(array(
                    'likes' => $blog->likes
                ));
                die();

            /*if(isset($_POST['id'])){
                $blog=BlogLike::model()->findByAttributes(array('blog_id'=>$_POST['id'],'user_id'=>yii::app()->user->getId()));
            }
            if(!$blog){
                $blog=new BlogLike;
                $blog->blog_id=$_POST['id'];
                $blog->user_id=yii::app()->user->getId();
                $blog->save();
                $b=Blog::model()->findByPk($_POST['id']);
                $this->sendJsonResponse(array(
                    'likes' => $b->blogLikes
                ));
                die();
            }else{
                $blog->delete();
                $b=Blog::model()->findByPk($_POST['id']);
                $this->sendJsonResponse(array(
                    'likes' => $b->blogLikes
                ));
                die();
            }*/
        }
        $this->sendJsonResponse(array(
            'error' => true,
        ));

    }
}