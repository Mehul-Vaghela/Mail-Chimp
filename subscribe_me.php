<?php
/**
 * Created by PhpStorm.
 * User: Mv
 * Date: 12/31/2017
 * Time: 11:09 AM
 */

$apiKey = 'Your mailchimp api key';
$user_email='Email to be subcribe';
$request_type='PUT';
$userid = md5(strtolower($user_email));
$post_data['user_email']=$user_email;

$list_id='12321432';//Id of the list customers
$url_params='lists/'.$list_id.'/members/' . $userid;
$dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/'.$url_params;
$auth = base64_encode( 'user:'. $apiKey );
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
    'Authorization: Basic '. $auth));
curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request_type);

    $data = array(
        'apikey'        => $apiKey,
        'email_address'=>$post_data['user_email'],
        'status'=>'subscribed',// "subscribed","unsubscribed","cleaned","pending"
        'merge_fields'  => [
            'FNAME'     => '',
            'LNAME'     => ''
        ]
    );
    $json = json_encode($data);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);


curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
curl_close($ch);
//return $result;
//print_r($result);die;