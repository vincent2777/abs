<?php

/* Script developed by Vincent Kalu to Send SMS using Twilio to Customers who made Enquiry on
Artificial Intelligence technologies Portal */

require 'twilio-sdk/src/Twilio/autoload.php';
use Twilio\Rest\Client;

session_start();
include "db_connect.php";

  //get settings
  $setSql = "SELECT * FROM settings";
  $setQuery = $connect->query($setSql);
  $setRow = $setQuery->fetch_array();
  $token = $setRow["twilio_token"];
  $sid = $setRow["twilio_ssid"];

if(isset($_POST["customer_id"])){
    echo json_encode("can send sms");

    $message = $_POST["message"];
        $account_sid = $sid;
        $auth_token = $token;
        $sender_id = $_POST["sender_id"];
        $client = new Client($account_sid, $auth_token);
    
        $message = $client->messages
            ->create(
                $phone, // to
                ["from" =>  $sender_id, "body" => $message]
            );

            if ($message->status == "queue") {
                echo "<div class='alert alert-success'>Birthday Message Sent Successfully</div>";
            } else {
                echo "<div class='alert alert-error'>Oops!! Birthday Message Failed to Deliver. Please try again</div>";
            }

}

?>