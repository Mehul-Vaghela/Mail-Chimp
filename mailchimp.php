<?php
/**
 * Created by PhpStorm.
 * User: Mv
 * Date: 12/29/2017
 * Time: 7:28 PM
 */
require 'functions.php';
$user_email='mgvaghela31@gmail.com';
$get_method_params = $_POST['Method'];
$function_obj = new functions();
if ($get_method_params == 'getMailchimpLists') {
    $result = $function_obj->get_mailchimp_lists();
    echo json_decode($result);die;
}
if($get_method_params == 'checkEmailSubcription'){
    $result = $function_obj->check_email_subscription($user_email);
    print_r($result);die;
}

if($get_method_params == 'unSubcribeUser'){
    $result = $function_obj->unsubcribe_user($_POST['ListId'],$user_email);
    print_r($result);die;
}

if($get_method_params == 'SubcribeUser'){
    $result = $function_obj->subcribe_user($_POST['ListArray'],$user_email,$_POST['FisrtName'],$_POST['Last_name']);
    print_r($result);die;
}



?>
