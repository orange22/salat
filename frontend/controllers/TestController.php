<?php

class TestController extends FrontController
{
    public function init()
    {
    	parent::init();
       Yii::import('common.extensions.yii-mail.*');
    }
	
    public function actionPrint2() {
        $tbl .= '
        <table style="padding: 5px;" border="1">
            <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>№</td>
                <td>Наименование</td>
                <td>Кол</td>
                <td>Цена</td>
                <td>Сумма</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </tbody>
        </table>';
        echo $tbl;
    }
    
	public function actionPrint() {
	    $oid=$_GET['oid'];
	    $pdf = Yii::createComponent('common.extensions.tcpdf.ETcPdf', 
                            'P', 'cm', 'A4', true, 'UTF-8');
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor("Lpovar");
        $pdf->SetTitle("Товарный чек");
        //$pdf->SetSubject("TCPDF Tutorial");
        $pdf->SetKeywords("TCPDF, PDF, example, test, guide");
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('freeserif', '', 11);
       
       
       $order=Order::model()->with(array('orderDishes'=>array('with'=>'dish'),'user','orderDrinks'=>array('with'=>'order'),'discount'))->find('t.id=:id',array(':id'=>$oid));
       $user=User::model()->findByPk($order->user_id);   
       $tbl .= '
        <table>
            <tr>
                <td width="125px">
                    <img height="70px" style="float:left;" src="/images/logo_small.jpg"/>
                </td>
                <td><br/><br/><br/>Личный Повар<br/>
                    Lpovar.com.ua<br/>
                    Тел. 044 360 60 09
                </td>
            </tr>
        </table>
        <div align="center"><br/><strong>Товарный чек</strong><br/></div>        
        <table style="padding: 5px;" border="1">
            <tbody>
            <tr>
                <td width="20px">№</td>
                <td width="320px">Наименование</td>
                <td width="30px">Кол</td>
                <td width="70px">Цена</td>
                <td width="70px">Сумма</td>
            </tr>
            ';
       $cnt=1;
       //$total_all=0;
       $total_dish=0;
       $total_drink=0;
       foreach($order->orderDishes as $dish){
       $total=null;
       $total=$dish->dish->price*$dish->quantity;
       
       $total_dish=$total_dish+$total;
       
       $tbl .= "
            <tr>
                <td>{$cnt}</td>
                <td>{$dish->dish->title}</td>
                <td>{$dish->quantity}</td>
                <td>{$dish->dish->price} грн.</td>
                <td>{$total} грн.</td>
            </tr>";
       $cnt++;     
       }
       
       foreach($order->orderDrinks as $drink){
       $total=null;
       $total=$drink->drink->price*$drink->quantity;
       $total_drink=$total_drink+$total;
       $tbl .= "
            <tr>
                <td>{$cnt}</td>
                <td>{$drink->drink->title}</td>
                <td>{$drink->quantity}</td>
                <td>{$drink->drink->price} грн.</td>
                <td>{$total} грн.</td>
            </tr>";
       $cnt++;     
       }
       
        $discount=100;
        if($user->discount>0){
            $discount=$user->discount;
        }elseif($order->discount_id>1){
            $disc=Discount::model()->findByPk($order->discount_id);
            if($disc->discount>0)
            $discount=$disc->discount;
        }
        $discountpercent=null;
        if($discount>0){
            $discountpercent=$discount;
            $discount=1-($discount/100);
        }
    
        if(!$discount)
            $discount=1;
       
       
       $total_all=$total_dish*$discount+$total_drink;
       
       if($discount>1)
            $totaltext='Всего (со скидкой - '.$discountpercent.'% на наборы блюд)';
       else{
            $totaltext='Всего';
       }
       
       $tbl .= '
            <tr>
            <td colspan="4" align="right">'.$totaltext.':</td><td>'.$total_all.' грн.</td>
            </tr>
            </tbody>
        </table>
        <br/><br/>';
        $tbl .= '
        <strong>Доставка:</strong> '.$order->delivery_addr.'<br/>';
        $tbl .= '
        <strong>Тел.</strong> '.$order->phone.' '.$order->user->name.'<br/>';
        $tbl .= '
        <strong>Дата и время:</strong> '.$order->delivery_from.' - '.$order->delivery_till.'<br/>
       ';
       //echo $tbl;
       $pdf->writeHTML($tbl, true, false, false, false, '');
        
        
        
        //$pdf->Cell(0,10,"Example 002",1,1,'C');
       $pdf->Output("example_002.pdf", "I");
        /*
        if ($checked = $_POST['checkedOrder']) {
                    $pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', 'P', 'cm', 'A4', true, 'UTF-8');
                    $pdf->SetCreator(PDF_CREATOR);
                    $pdf->SetAuthor("Belyakov Yuriy");
                    $pdf->SetTitle("Orders");
                    $pdf->SetSubject("Orders");
                    $pdf->SetKeywords("Orders");
                    $pdf->setPrintHeader(false);
                    $pdf->setPrintFooter(false);
                    $pdf->AddPage();
                    $pdf->SetFont('freeserif', '', 14);
                    $tbl = "" . date('d.m.Y', time()) . "<br>";
                    $pdf->SetFont('freeserif', '', 10);
                    $tbl .= '';
         
                    $printOrders = null;
                    foreach ($checked as $k => $v) {
                        $order = Order::model()->findByPk((int) $v);
                        $priority = Order::model()->getPriority($order->priority);
                        $tbl .= "";
                    }
                    $tbl .= "<table style="padding: 5px;" border="1"><tbody><tr bgcolor="#ccc"><th width="30%"><strong>РљР»РёРµРЅС‚</strong></th><th width="70%"><strong>РЎРѕРґРµСЂР¶Р°РЅРёРµ</strong></th></tr><tr><td><strong>РђРґСЂРµСЃ:</strong> {$order->client->title} - {$order->client->division} ({$order->client->address})<br><strong>РџСЂРёРѕСЂРёС‚РµС‚:</strong> {$priority}</td><td>{$order->text}</td></tr></tbody></table>";
         
                    $pdf->writeHTML($tbl, true, false, false, false, '');
         
                    $pdf->Output('orders.pdf', 'I');
                } else {
                    Yii::app()->user->setFlash('myOrder', 'Р’С‹Р±РµСЂРёС‚Рµ Р·Р°СЏРІРєРё РґР»СЏ РїРµС‡Р°С‚Рё.');
                    ;
                    $this->redirect(array('/order/myorder'));
                }*/
        
    }
  
}