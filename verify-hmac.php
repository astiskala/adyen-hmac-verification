<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Adyen HMAC Verification Sample</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
</head>
<body>
    <h1>Adyen HMAC Verification Sample</h1>
    <h2>Input Parameters</h2>
    <form action="validate-hmac.php" method="post">
        <label for="hmacKey">HMAC key:</label><br>
        <input type="text" id="hmacKey" name="hmacKey" value="6146B85E4073AD3558C85FAD07FCBB187AB62224DE92C93BE1661F8CD16027B5"><br><br>
        <label for="providedSignature">Provided signature:</label><br>
        <input type="text" id="providedSignature" name="providedSignature" value="nVQwmVew1F2uGoHTfnnQEy2Q/GZu7/nIdNgfYlVrDkY="><br><br>
        <label for="pspReference">pspReference:</label><br>
        <input type="text" id="pspReference" name="pspReference" value="8536111812477166"><br><br>
        <label for="originalReference">originalReference:</label><br>
        <input type="text" id="originalReference" name="originalReference" value=""><br><br>
        <label for="merchantAccountCode">merchantAccountCode:</label><br>
        <input type="text" id="merchantAccountCode" name="merchantAccountCode" value="AdamStiskala35209"><br><br>
        <label for="merchantReference">merchantReference:</label><br>
        <input type="text" id="merchantReference" name="merchantReference" value="AdamStiskala-7d8a2e4c-4080-4604-8602-51a13d287cc3"><br><br>
        <label for="value">value:</label><br>
        <input type="text" id="value" name="value" value="795"><br><br>
        <label for="currency">currency:</label><br>
        <input type="text" id="currency" name="currency" value="AUD"><br><br>
        <label for="eventCode">eventCode:</label><br>
        <input type="text" id="eventCode" name="eventCode" value="AUTHORISATION"><br><br>
        <label for="success">success:</label><br>
        <input type="text" id="success" name="success" value="true"><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>

<?php

$hmacKey = $_POST['hmacKey'];
if ($hmacKey) {
  echo "<h2>Verification</h2>";

  $providedSignature = $_POST['providedSignature'];

  $key = pack("H*", $hmacKey);

  $kcvInput = "00000000";
  $kcv = strtoupper(substr(bin2hex(hash_hmac('sha256', $kcvInput, $key, true)), -6));
  echo "KCV: <pre>$kcv</pre><br>";

  $pspReference = $_POST['pspReference'];
  $originalReference = $_POST['originalReference'];
  $merchantAccountCode = $_POST['merchantAccountCode'];
  $merchantReference = $_POST['merchantReference'];
  $value = $_POST['value'];
  $currency = $_POST['currency'];
  $eventCode = $_POST['eventCode'];
  $success = $_POST['success'];

  $dataToSign = "$pspReference:$originalReference:$merchantAccountCode:$merchantReference:$value:$currency:$eventCode:$success";
  echo "Sign data: <pre>$dataToSign</pre><br>";

  $calculatedSignature = base64_encode(hash_hmac('sha256', $dataToSign, $key, true));
  echo "Calculated Signature: <pre>$calculatedSignature</pre><br>";
  echo "Provided Signature: <pre>$providedSignature</pre><br>";

  if ($calculatedSignature == $providedSignature) {
    echo "<strong>Signatures match!<strong>";
  } else {
    echo "<strong>Signatures do not match</strong>";
  }
}

?>
