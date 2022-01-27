<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
</head>
<body>


<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.sandbox.nowpayments.io/v1/estimate?amount=3999.5000&currency_from=usd&currency_to=btc',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'x-api-key: RJ5GPJM-21R4T1N-J9Q8G7Z-R0SM67M'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

?>

<script type="text/javascript">
	var myHeaders = new Headers();
myHeaders.append("x-api-key", "RJ5GPJM-21R4T1N-J9Q8G7Z-R0SM67M");

var requestOptions = {
  method: 'GET',
  headers: myHeaders,
  redirect: 'follow'
};

fetch("https://api.sandbox.nowpayments.io/v1/estimate?amount=3999.5000&currency_from=usd&currency_to=btc", requestOptions)
  .then(response => response.text())
  .then(result => console.log(result))
  .catch(error => console.log('error', error));
</script>
</body>
</html>