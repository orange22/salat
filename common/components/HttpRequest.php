<?php
class HttpRequest extends CHttpRequest
{
    protected $_customCsrfToken = null;

    public function setCsrfToken($csrfToken)
    {
        $this->_customCsrfToken = $csrfToken;
    }

    public function getCsrfToken()
    {
        if($this->_customCsrfToken !== null)
            return $this->_customCsrfToken;

        return parent::getCsrfToken();
    }
}