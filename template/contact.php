<?php
define("WEBMASTER_EMAIL", 'foxsash82@gmail.com'); // Enter your e-mail
 
error_reporting (E_ALL); 
 
if(count($_POST))
{

	
$name = htmlspecialchars($_POST['your-name']);
$email = $_POST['your-email'];
$message = htmlspecialchars($_POST['your-message']);
$subject = htmlspecialchars($_POST['your-subject']);
 
$error = array();
 
 
if(empty($subject))
{
$error[] = 'Please enter <strong>subject</strong>';
}

if(empty($name))
{
$error[] = 'Please enter your <strong>name</strong>';
}
 
 
if(empty($email))
{
$error[] = 'Please enter your <strong>e-mail</strong>';
}elseif(!filter_var($email, FILTER_VALIDATE_EMAIL))
{ 
$error[] = 'E-mail is incorrect';
}
 
 
if(empty($message) || empty($message{15})) 
{
$error[] = "Enter message more than <strong>15</strong> characters";
}
 
if(empty($error))
{ 
$message = 'Name ' . $name . '
Email: ' . $email . '
Mssage: ' . $message;
$mail = mail(WEBMASTER_EMAIL, '[Message from WebSite] '.$subject, $message,
     "From: ".$name." \r\n"
    ."Reply-To: ".$email."\r\n"
    ."X-Mailer: PHP/" . phpversion());
 
if($mail)
{
echo 'OK';
}
 
}
else
{
echo '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Warning!</h3>'.implode('<br/>', $error).'</div>';
}
}