<?php 
$device_id =array("fxPVGy88TFa6StpLovMPwb:APA91bFhahEHTddsJ-FK23Zh0KNFTZ054X1E1gk6bvN6VkKk8SLrALSoTQKoTsXaGACFvDImSd8DcF-lFDrYmC6zvUOYVwvoPEdQuXzhNgR5lrV4dcQxwhHHbYxAI6D5z-88xRe3SYMN");


$message = "order places!";
$body = "kk body";
    //API URL of FCM
    $url = 'https://fcm.googleapis.com/fcm/send';

    /*api_key available in:
    Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/    
    $api_key = 'AAAAAvfcUdE:APA91bGvvlYHXGyXa5Dw0nOcROdNYL8_Oijj7XgkYUMW3b8gJo6cn2JtplvTeXBLUMvKAyMf7c-O1ksrC01NtiHw0kWsZRwusxslmUAV-LODwrJW_PjEvfEjOi7PkFIqVvkRFA_MGOOl';
                
    $fields = array (
        'registration_ids' => array (
                $device_id
        ),
        'data' => array (
                "title" => $message
        )
    );

    //header includes Content type and api key
    $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$api_key
    );
                
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;

?>