<?php

     $captcha = filter_input(INPUT_POST, 'g-recaptcha-response', FILTER_UNSAFE_RAW);

    if(!$captcha){
        http_response_code(400); exit;
    }

    $secretKey = "6LdqmIAjAAAAAANr72y1tKG8Jd_PCcNRCbX66-GC";
    $ip = $_SERVER['REMOTE_ADDR'];
    $url = 'https://www.google.com/recaptcha/api/siteverify';

    $data = array('secret' => $secretKey, 'response' => $captcha);

    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context  = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $responseKeys = json_decode($response,true);

     if($responseKeys["success"]) {

        $name =$_POST["name"];
        $from =$_POST["email"];
        $phone =$_POST["phone"];
        $message=$_POST["message"];
        $receiver="soadenelaire@gmail.com";
        $subject="Detalles del formulario de contacto";

        $message = "
            <html>
            <head>
            <title>Detalles del formulario de contacto</title>
            </head>
            <body>
            <table width='50%' border='0' align='center' cellpadding='0' cellspacing='0'>
            <tr>
            <td width='50%' align='right'>&nbsp;</td>
            <td align='left'>&nbsp;</td>
            </tr>
            <tr>
            <td align='right' valign='top' style='border-top:1px solid #dfdfdf; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 5px 7px 0;'>Nombre:</td>
            <td align='left' valign='top' style='border-top:1px solid #dfdfdf; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 0 7px 5px;'>".$name."</td>
            </tr>
            <tr>
            <td align='right' valign='top' style='border-top:1px solid #dfdfdf; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 5px 7px 0;'>Email:</td>
            <td align='left' valign='top' style='border-top:1px solid #dfdfdf; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 0 7px 5px;'>".$from."</td>
            </tr>
            <tr>
            <td align='right' valign='top' style='border-top:1px solid #dfdfdf; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 5px 7px 0;'>Teléfono:</td>
            <td align='left' valign='top' style='border-top:1px solid #dfdfdf; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 0 7px 5px;'>".$phone."</td>
            </tr>
            <tr>
            <td align='right' valign='top' style='border-top:1px solid #dfdfdf; border-bottom:1px solid #dfdfdf; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 5px 7px 0;'>Mensaje:</td>
            <td align='left' valign='top' style='border-top:1px solid #dfdfdf; border-bottom:1px solid #dfdfdf; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 0 7px 5px;'>".$message."</td>
            </tr>
            </table>
            </body>
            </html>
        ";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: <'.$from.'>' . "\r\n";

        if(mail($receiver,$subject,$message,$headers)) {
            echo '<div id="success-project-contact-form" class="no-margin-lr send-success">¡El mensaje ha sido enviado!</div>';
        } else {
            http_response_code(400); exit;
        }

     } else {
        http_response_code(400); exit;
     }

?>