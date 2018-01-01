<?php
/**
 * Created by PhpStorm.
 * User: Mv
 * Date: 12/29/2017
 * Time: 7:26 PM
 */

class functions
{
    public function get_mailchimp_lists(){

        $url_params = 'lists';
        $request_type='GET';
        $request_about='get_mailchimp_lists';

        $post_data='';
        $lists=$this->request_to_mailchimp_api($url_params,$request_type,$request_about,$post_data);
        return json_encode($lists);

    }

    public function check_email_subscription($user_email){

        $request_type='GET';
        $request_about='check_email_subscription';
        $userid = md5(strtolower($user_email));

        $get_lists_result=json_decode($this->get_mailchimp_lists());
        $get_lists=json_decode($get_lists_result);
        $lists=array();
        $n=0;
        foreach ($get_lists->lists as $mail_lists){
            $url_params='lists/'.$mail_lists->id.'/members/' . $userid;
            $lists[$n]['response']=$this->request_to_mailchimp_api($url_params,$request_type,$request_about,$user_email);
            $lists[$n]['list_id']=$mail_lists->id;
            $lists[$n]['list_name']=$mail_lists->name;
            $n++;

        }
        return json_encode($lists);

    }

    public function unsubcribe_user($list_id,$user_email){

        $request_type='PUT';
        $request_about='unsubcribe_user';
        $userid = md5(strtolower($user_email));
        $url_params='lists/'.$list_id.'/members/' . $userid;
        $result=$this->request_to_mailchimp_api($url_params,$request_type,$request_about,$user_email);
        return json_encode($result);

    }

    public function subcribe_user($list_array,$user_email,$first_name,$last_name){
        $request_type='PUT';
        $request_about='subcribe_user';
        $userid = md5(strtolower($user_email));
        $post_data=array();
        $post_data['user_email']=$user_email;
        $post_data['first_name']=$first_name;
        $post_data['last_name']=$last_name;
        $n=0;
        foreach ($list_array as $list_id){
            $url_params='lists/'.$list_id.'/members/' . $userid;
            $result[$n]=$this->request_to_mailchimp_api($url_params,$request_type,$request_about,$post_data);
            $n++;
        }

        return json_encode($result);
    }

    public function request_to_mailchimp_api($url_params,$request_type,$request_about,$post_data){

        $apiKey = '5c75d433cc6b2eecf898f41a68ff05b6-us17';
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

        if($request_about == 'get_mailchimp_lists'){
            $data = array(
                'apikey'        => $apiKey,
            );
            $json = json_encode($data);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        }
        if($request_about == 'check_email_subscription'){
            $data = array(
                'apikey'        => $apiKey,
                'email_address'=>$post_data
            );
            $json = json_encode($data);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        }
        if($request_about == 'unsubcribe_user'){
            $data = array(
                'apikey'        => $apiKey,
                'email_address'=>$post_data,
                'status'=>'unsubscribed',// "subscribed","unsubscribed","cleaned","pending"
            );
            $json = json_encode($data);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        }

        if($request_about == 'subcribe_user'){
            $data = array(
                'apikey'        => $apiKey,
                'email_address'=>$post_data['user_email'],
                'status'=>'subscribed',// "subscribed","unsubscribed","cleaned","pending"
                'merge_fields'  => [
                    'FNAME'     => $post_data['first_name'],
                    'LNAME'     => $post_data['last_name']
                ]
            );
            $json = json_encode($data);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
//        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $result;
    }

}
