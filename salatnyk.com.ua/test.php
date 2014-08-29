<?
$_POST['field']='RegisterForm[email]';
preg_match("'RegisterForm\[(\w+)\]?'", $_POST['field'], $match);
print_r($match);
?>