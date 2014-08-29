<?php
/**
 * Base mail form
 * @uses Option
 */
class MailForm extends CFormModel
{
  public function init()
  {
    Yii::import('common.extensions.yii-mail.YiiMailMessage');
    parent::init();
  }

  /**
   * Send mail
   *
   * @param array $opts [body, subject, to, from email, from name]
   * @return int Number of delivered mails
   */
  public function send($opts = array(), $lang = null)
  {
    $opts = $this->initOpts($opts, $lang);

    $body = $opts['body'];
    foreach($this->getAttributes() as $key => $value)
      $body = str_replace("{{$key}}", nl2br(CHtml::encode(strip_tags($value))), $body);
    $body = preg_replace('/\{[\w\d_]\}/i', '', $body);

    $message = new YiiMailMessage();
    $message->setBody($body, 'text/html');
    $message->setSubject($opts['subject']);
    $message->setTo($opts['to']);
    $message->setFrom(array(
      $opts['fromEmail'] => $opts['fromName']
    ));

    return Yii::app()->mail->send($message);
  }

  /**
   * Init mail options
   *
   * @uses Option model
   * @param array $opts
   * @return array
   */
  protected function initOpts($opts = array(), $lang = null)
  {
    $key = strtolower(substr(get_class($this), 0, strlen(get_class($this)) - 4));

    $default = array(
      'to' => preg_split('/(,\s*)+/', Option::getOpt("email.{$key}.to", $lang), -1, PREG_SPLIT_NO_EMPTY),
      'body' => Option::getOpt("email.{$key}.body", $lang),
      'subject' => Option::getOpt("email.{$key}.subject", $lang),
      'fromName' => Option::getOpt('site.title', $lang),
      'fromEmail' => Option::getOpt("email.{$key}.sender", $lang),
    );

    return CMap::mergeArray($default, $opts);
  }
}
