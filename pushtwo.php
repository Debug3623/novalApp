<?php 

$registration_ids = array("fxPVGy88TFa6StpLovMPwb:APA91bFhahEHTddsJ-FK23Zh0KNFTZ054X1E1gk6bvN6VkKk8SLrALSoTQKoTsXaGACFvDImSd8DcF-lFDrYmC6zvUOYVwvoPEdQuXzhNgR5lrV4dcQxwhHHbYxAI6D5z-88xRe3SYMN");

// $registration_ids = array(
// 'registration_ids' = > 'fxPVGy88TFa6StpLovMPwb:APA91bFhahEHTddsJ-FK23Zh0KNFTZ054X1E1gk6bvN6VkKk8SLrALSoTQKoTsXaGACFvDImSd8DcF-lFDrYmC6zvUOYVwvoPEdQuXzhNgR5lrV4dcQxwhHHbYxAI6D5z-88xRe3SYMN'
// );
$message= array
(
    'body'   => 'here is a message. message',
    'title'     => 'This is a title. title',
    'subtitle'  => 'This is a subtitle. subtitle',
    'tickerText'    => 'Ticker text here...Ticker text here...Ticker text here',
    'vibrate'   => 1,
    'sound'     => 1,
    'largeIcon' => 'large_icon',
    'smallIcon' => 'small_icon'
);

public function send($registration_ids, $message) {
    // $fields = array(
    //     'registration_ids' => $registration_ids,
    //     'data' => $message,
    // );

            
    // $fields = array (
    //     'registration_ids' => array (
    //             $registration_ids
    //     ),
    //     'data' => array (
    //             "title" => $message
    //     )
    // );


$fields = array
(
    'registration_ids'  => $registration_ids,
    'data'          => $message
);

    return $this->sendPushNotification($fields);
}

/*
* This function will make the actuall curl request to firebase server
* and then the message is sent 
*/
private function sendPushNotification($fields) {

    //importing the constant files
    // require_once 'Config.php';

    //firebase server url to send the curl request
    $url = 'https://fcm.googleapis.com/fcm/send';
 $FIREBASE_API_KEY = 'AAAAAvfcUdE:APA91bGvvlYHXGyXa5Dw0nOcROdNYL8_Oijj7XgkYUMW3b8gJo6cn2JtplvTeXBLUMvKAyMf7c-O1ksrC01NtiHw0kWsZRwusxslmUAV-LODwrJW_PjEvfEjOi7PkFIqVvkRFA_MGOOl';
           
    //building headers for the request
    $headers = array(
        'Authorization: key=' . $FIREBASE_API_KEY,
        'Content-Type: application/json'
    );

    //Initializing curl to open a connection
    $ch = curl_init();

    //Setting the curl url
    curl_setopt($ch, CURLOPT_URL, $url);

    //setting the method as post
    curl_setopt($ch, CURLOPT_POST, true);

    //adding headers 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //disabling ssl support
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    //adding the fields in json format 
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    //finally executing the curl request 
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }

    //Now close the connection
    curl_close($ch);

    //and return the result 
    return $result;
  }
