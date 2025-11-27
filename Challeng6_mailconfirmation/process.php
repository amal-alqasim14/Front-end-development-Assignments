<?php
function clean($v){
  return htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8');
}

$firstname = clean($_POST['firstname'] ?? '');
$lastname  = clean($_POST['lastname'] ?? '');
$email     = clean($_POST['email'] ?? '');
$phone     = clean($_POST['phone'] ?? '');
$item      = clean($_POST['item'] ?? '');
$quantity  = intval($_POST['quantity'] ?? 1);
$notes     = clean($_POST['notes'] ?? '');

$errors=[];
if($firstname==''||$lastname==''||$email==''||$item==''){
  $errors[]='Vul alle verplichte velden in.';
}
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
  $errors[]='Ongeldig e-mailadres.';
}
if($quantity<1){$errors[]='Aantal moet minimaal 1 zijn.';}

if($errors){
  header('Content-Type:text/html;charset=utf-8');
  echo "<h2>Fouten:</h2><ul>";
  foreach($errors as $e){echo "<li>$e</li>";}
  echo '</ul><a href="javascript:history.back()">Terug</a>';
  exit;
}

$message = "
  <h1 style='color:#1f4e79'>Nieuwe bestelling</h1>
  <p><strong>Naam:</strong> $firstname $lastname<br>
  <strong>E-mail:</strong> $email<br>
  <strong>Telefoon:</strong> $phone<br>
  <strong>Product:</strong> $item<br>
  <strong>Aantal:</strong> $quantity<br>
  <strong>Opmerking:</strong> ".($notes ?: '-')."</p>
";

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type:text/html;charset=UTF-8\r\n";
$headers .= "From: Dierenproducten Shop <no-reply@hera.local>\r\n";
$headers .= "Reply-To: $email\r\n";

$recipient = "560205@student.fontys.nl";
$subject   = "Nieuwe bestelling: $item (x$quantity)";

if(mail($recipient,$subject,$message,$headers)){
  header("Location: success.html");
  exit;
}else{
  echo "<p>Verzenden mislukt. Controleer de mailinstellingen op HERA.</p>";
}
