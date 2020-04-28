<?php
$input = new inputFilter();
$staffusername = $input->postFilter("username");
$staffpassword = $input->postFilter("password");
$status = false;
if((strlen($staffusername) > 0) && (strlen($staffpassword) > 0))
{
    if($session->login_with_username_password($staffusername,$staffpassword) == true)
    {
        $status = true;
        echo "logged in ^+^";
        $redirect = "here";
    }
    else
    {
        echo "Incorrect Username/Password or the account is locked";
    }
}
else
{
    echo "Username or Password are empty";
}
?>
