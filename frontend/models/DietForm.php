<?php

class DietForm extends CFormModel
{
	public $email;
	public $title;
    public $detail_text;
	
	

	private $_identity;

	public function rules()
	{
		return array(
			// username and password are required
			array('email, title, detail_text', 'required'),
		);
	}

	public function save()
	{
        $message = new YiiMailMessage;
        $message_body='
                        Новый вопрос диетологу!<br><br>
                        Имя: '.$this->title.'<br>
                        Email: '.$this->email.'<br>
                        Имя: '.$this->detail_text.'<br>
                        ';
        $message->setBody($message_body, 'text/html');
        $message->subject = 'Новый вопрос диетологу';
        $emails=explode(',',Option::getOpt('diet_email'));
        if(count($emails)>0){
            foreach($emails as $email){
                $message->addTo($email);
            }
        }else
        $message->addTo(Option::getOpt('diet_email'));

        $message->from = Yii::app()->params['adminEmail'];
        return Yii::app()->mail->send($message);

	}
}
