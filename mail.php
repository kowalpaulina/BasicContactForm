<?php
include("functions.php");

if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    header('Content-Type: text/html; charset=utf-8');
    die('Dostęp zabroniony');
}

$fname = $email = $message = '';
$error = array();
$dataPerson = array();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    function test_input($data) {
          $data = trim($data);
          $data = stripslashes($data);
          $data = htmlspecialchars($data);
          return $data;
        }

    $fname = test_input($_POST["fname"]);
    $email = test_input($_POST["email"]);
    $message = test_input($_POST["message"]);
    
        if($fname==''){
            $error['fnameError'] = "Uzupełnij imię!"; 
        }else{
            $dataPerson['fname'] = $fname;
        }
    
        if(!validateTypes("mail",$email)){
            $error['emailError'] = "Uzupełnij adres email!";
        }else{
            $dataPerson['email'] = $email;
        }

        if($message==''){
            $error['messageError'] = "Pozostaw wiadomość!";
        }else{
            $dataPerson['message'] = $message;
        }
    
        if(count($error) > 0) {
            echo json_encode(array('error' => $error));
        }else{
            
            
            $to = 'your@mail.com';
            $subject = 'Wiadomość ze strony portfolio od ' .$_POST['fname'];
            $message = $_POST['message'];
            $headers = 'From: ' . $_POST['email'] . "\r\n" .
            'Reply-To: ' . $_POST['email'] . "\r\n" .
            'Content-Type: text/plain;charset=utf-8\r\n' .
            'X-Mailer: PHP/' . phpversion();

            $mail_sent = mail($to, $subject, $message, $headers);            
            if($mail_sent) {
                echo json_encode(array(
                    'success' => 'wiadomość została wysłana.', 'dataPerson' => $dataPerson));
            } else {
                echo json_encode(array(
                    'errorSentMail' => 'Wystąpił błąd podczas wysyłania wiadomości'));
            }
        }
}
?>