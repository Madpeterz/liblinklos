<?php
sleep(1);
$input = new inputFilter();
$slusername = $input->postFilter("slusername");
$token = $input->postFilter("token");
$newpw1 = $input->postFilter("newpassword1");
$newpw2 = $input->postFilter("newpassword2");

$status = false;
$failed_on = "";
if($newpw1 == $newpw2)
{
    if(strlen($newpw1) >= 7)
    {
        $username_bits = explode(" ",$slusername);
        if(count($username_bits) == 1)
        {
            $username_bits[] = "Resident";
        }
        $slusername = implode(" ",$username_bits);
        $client = new client();
        $status = false;
        if($client->load_by_field("name",$slusername) == true)
        {
            if($client->get_reset_code() == $token)
            {
                if($client->get_reset_expires() > time())
                {
                    $session_helper = new session_control();
                    $session_helper->attach_member($client);
                    $update_status = $session_helper->update_password($newpw1);
                    if($update_status["status"] == true)
                    {
                        $client->set_field("reset_code",null);
                        $client->set_field("reset_expires",time()-1);
                        $update_status = $client->save_changes();
                        if($update_status["status"] == true)
                        {
                            $status = true;
                            $redirect = "login";
                            echo "Password updated please login";
                        }
                        else
                        {
                            $failed_on = "Unable to finalize changes to your account";
                        }
                    }
                    else
                    {
                        $failed_on = "Something went wrong updating your password";
                    }
                }
                else
                {
                    $failed_on = "Your token has expired, please request a new one";
                }
            }
        }
    }
    else
    {
        $failed_on =  "New password is to short min length 7";
    }
}
else
{
    $failed_on = "New passwords do not match, how did you fuck that up";
}

if($status == false)
{
    if($failed_on == "")
    {
        echo "Unable to update account, please ask a admin to check the account is not banned, deleted, blocked and your username is correct";
    }
    else
    {
        echo $failed_on;
    }
}


?>
