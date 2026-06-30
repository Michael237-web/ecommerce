<?php
class Mpesa {
    private $consumerKey = "YOUR_CONSUMER_KEY";
    private $consumerSecret = "YOUR_CONSUMER_SECRET";
    private $shortcode = "YOUR_SHORTCODE";
    private $passkey = "YOUR_PASSKEY";
    private $baseUrl = "https://sandbox.safaricom.co.ke";

    public function getToken() {
        $url = $this->baseUrl . "/oauth/v1/generate?grant_type=client_credentials";
        $credentials = base64_encode($this->consumerKey . ":" . $this->consumerSecret);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["Authorization: Basic " . $credentials]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response)->access_token;
    }

    public function stkPush($phone, $amount, $accountReference = "MichaelStore") {
        $token = $this->getToken();
        $timestamp = date("YmdHis");
        $password = base64_encode($this->shortcode . $this->passkey . $timestamp);
        $url = $this->baseUrl . "/mpesa/stkpush/v1/processrequest";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $token,
            "Content-Type: application/json"
        ]);
        $data = [
            "BusinessShortCode" => $this->shortcode,
            "Password" => $password,
            "Timestamp" => $timestamp,
            "TransactionType" => "CustomerPayBillOnline",
            "Amount" => $amount,
            "PartyA" => $phone,
            "PartyB" => $this->shortcode,
            "PhoneNumber" => $phone,
            "CallBackURL" => "https://yourdomain.com/mpesa_callback.php",
            "AccountReference" => $accountReference,
            "TransactionDesc" => "Order Payment"
        ];
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }
}
?>