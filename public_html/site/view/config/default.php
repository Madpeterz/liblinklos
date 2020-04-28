<?php
$match_with = "newest";

$second_bot_config_set = new second_bot_config_set();
$second_bot_config_set->load_on_field("clientlink",$client->get_id());

$table_head = array("Name");
$table_body = array();
$template_parts["page_title"] .= "Save configs";

$loop = 1;
foreach($second_bot_config_set->get_all_ids() as $config_id)
{
    $second_bot_config = $second_bot_config_set->get_object_by_id($config_id);
    $entry = array();
    $entry[] = $loop;
    $use_name = $second_bot_config->get_config_uid();
    $entry[] = '<a href="[[url_base]]config/manage/'.$second_bot_config->get_config_uid().'">'.$second_bot_config->get_userName().'</a>';
    $table_body[] = $entry;
    $loop++;
}
echo render_table($table_head,$table_body);
?>
