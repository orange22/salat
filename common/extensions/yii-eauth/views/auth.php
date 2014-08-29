  <?php
	foreach ($services as $name => $service) {
		$html = '<span>Login with '.$service->title.'</span>';
		$html = CHtml::link($html, array($action, 'service' => $name, 'page'=>$_SERVER['REQUEST_URI']), array(
			'class' => 'fogin-fb ',
		));
		echo $html;
	}
  ?>