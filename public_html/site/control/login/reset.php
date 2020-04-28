<?php
sleep(1);
$input = new inputFilter();
$slusername = $input->postFilter("slusername");
$bits = explode("@",$slusername);
$contact_via = "sl";
$client = new client();

$username_bits = explode(" ",$slusername);
if(count($username_bits) == 1)
{
    $username_bits[] = "Resident";
}
$slusername = implode(" ",$username_bits);
$status = false;
$hide_error = false;
$real_error = "";

if($client->load_by_field("name",$slusername) == true)
{
    $uid = $client->create_uid("reset_code",8,10);
    if($uid["status"] == true)
    {
        $reset_url = $template_parts["url_base"]."login/resetwithtoken/".$uid["uid"];
        $client->set_field("reset_code",$uid["uid"]);
        $client->set_field("reset_expires",(time()+$unixtime_hour));
        $update_status = $client->save_changes();
        if($update_status["status"] == true)
        {
            $message = new message();
            $message->set_field("clientlink",$client->get_id());
            $message->set_field("message","Web panel reset link: ".$reset_url." expires in 1 hour \n (Token: ".$uid["uid"].")");
            $add_status = $message->create_entry();
            if($add_status["status"] == true)
            {
                $status = true;
                $redirect = "here";
                echo "reset started via SL";
            }
            else
            {
                $real_error = "Unable to create message";
            }
        }
        else
        {
            $real_error = "Unable to save changes";
        }
    }
    else
    {
        $real_error = "Unable to create uid";
    }
}
else
{
    $real_error = "Unable to find client";
}

if($status == false)
{
    if($hide_error == false) echo $real_error;
    else echo "Unable to reset avatar/staff account";
}
?>
