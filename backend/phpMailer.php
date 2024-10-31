<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//inladen van de phpmailer classes
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug    = 0; // 0 = is to be able to redirect. no errors will be diplayd;  debugging: 1 = errors and messages, 2 = messages only                                   
    $mail->isSMTP();    //protocol defineren
    $mail->SMTPAuth     = true; // authentificatie   
    $mail->Host         = 'vps.bg-techno.nl'; // servernaam
    $mail->Username     = 'testaccount@bsolutions.nl'; // SMTP username
    $mail->Password     = '[GhRXcE{8I1w'; // SMTP password  
    $mail->SMTPSecure   = 'tls'; // encryptie                   
    $mail->Port         = 587; //serverpoort 

    $mail->setFrom('belbandjse@hotmail.com'); // afzender     
    $mail->addAddress('stefano@bsolutions.nl'); //ontvanger // verander later het email adres naar support@bsolutions.nl

    $mail->isHTML(true); //html mail/ plain text                             
    $mail->Subject = 'uw verzoek om een bandjes te verwerken is verzonden'; // onderwerp
    // schrijf een bevestegings mail met de afspraak gegevens die de gebruiker heeft ingevuld.

    require_once("conn.php");

    //probeer deze code uit te voeren als het niet lukt geef dan een error message
    try {

        $query      = "SELECT * FROM typebandjes ORDER BY id ASC";
        $types      = $conn->prepare($query);
        $types->execute();

        foreach ($types as $type) {
            //hier worden de services opgehaald uit de database
            $type_id    = $type['id'];
            $type_name  = $type['type_name'];
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // try to convert the int to a description of the corrosponding data. this is for if its a permanent recording or not
    try{
        if ( $permanent == 1 ) {
            $permanent = "Ja";
        }
        else {
            $permanent = "Nee";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    
    // try to convert the int to a description of the corrosponding data. this is for the catagory of the purpuse of the recording or not
    // make a switch case for the different catagories
    switch ($type_id) {
        // voor de anw optie
        case 1:
            if ($type_id == 1) {
                $type_name = "ANW (Avond, Nacht & Weekend)";
                break;
            }

        // voor de wacht optie
        case 2:
            if ($type_id == 2) {
                $type_name = "Wachtbandje";
                break;
            }
            
        // voor de vakantie optie
        case 3:
            if ($type_id == 3) {
                $type_name = "Vakantie";
                break;
            }
            break;
      // voor de overige optie
        case 4:
            if ($type_id == 4) {
                $type_name = "Overige";
                break;
            }
      }

      try {

        $query      = "SELECT * FROM gebruikersgegevens ORDER BY id ASC";
        $records      = $conn->prepare($query);
        $records->execute();
        $records->Fetch(PDO::FETCH_ASSOC);

        foreach ($records as $record) {
            //hier worden de services opgehaald uit de database
            $audio_location     = $record['audio_location'];
            $audio_name         = $record['audio_file'];
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // retrive audio data from database with pdo
    $query      = "SELECT * FROM gebruikersgegevens ORDER BY id DESC LIMIT 1 ";
    $records      = $conn->prepare($query);
    $records->execute();
    $resultRecord = $records->Fetch(PDO::FETCH_ASSOC);
    
    foreach ($records as $record) {
        //hier worden de services opgehaald uit de database
        $audio_location     = $resultRecord['audio_location'];
        $audio_name         = $resultRecord['audio_file'];
    }
    
    // Create a JSON file and send it as an attachment
    $audioFilePath =  $audio_location . $audio_name;
    
    // Attach the audio content as a file to the email
    $mail->addAttachment($audio_location . $audio_name, );
 
      
    // inhoud van de mail 
    $mail->Body    =
        '
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <!-- Compiled with Bootstrap Email version: 1.4.0 -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <style type="text/css">
      body,table,td{font-family:Helvetica,Arial,sans-serif !important}.ExternalClass{width:100%}.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div{line-height:150%}a{text-decoration:none}*{color:inherit}a[x-apple-data-detectors],u+#body a,#MessageViewBody a{color:inherit;text-decoration:none;font-size:inherit;font-family:inherit;font-weight:inherit;line-height:inherit}img{-ms-interpolation-mode:bicubic}table:not([class^=s-]){font-family:Helvetica,Arial,sans-serif;mso-table-lspace:0pt;mso-table-rspace:0pt;border-spacing:0px;border-collapse:collapse}table:not([class^=s-]) td{border-spacing:0px;border-collapse:collapse}@media screen and (max-width: 600px){*[class*=s-lg-]>tbody>tr>td{font-size:0 !important;line-height:0 !important;height:0 !important}}
    </style>
  </head>
  <body style="outline: 0; width: 75%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; font-family: Helvetica, Arial, sans-serif; line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; margin: 0; padding: 0; border-width: 0;" bgcolor="#ffffff">
    <table class="body" valign="top" role="presentation" border="0" cellpadding="0" cellspacing="0" style="outline: 0; width: 100%; min-width: 100%; height: 100%; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; font-family: Helvetica, Arial, sans-serif; line-height: 24px; font-weight: normal; font-size: 16px; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; color: #000000; margin: 0; padding: 0; border-width: 0;" bgcolor="#ffffff">
      <tbody>
        <tr>
          <td valign="top" style="line-height: 24px; font-size: 16px; margin: 0;" align="left">
            <h1 style="padding-top: 0; padding-bottom: 0; font-weight: 500; vertical-align: baseline; font-size: 36px; line-height: 43.2px; margin: 0;" align="left">Bedankt voor het invullen van de benodigde informatie</h1>
            <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left">Uw verzoek om een bandje te maken is verzonden. Hieronder vind u de gegevens die u heeft ingevuld.</p>
            <div>
              <br>
              <div class="row" style="margin-right: -24px;">
                <table class="" role="presentation" border="0" cellpadding="0" cellspacing="0" style="table-layout: fixed; width: 100%;" width="100%">
                  <tbody>
                    <tr>
                      <td class="col" style="line-height: 24px; font-size: 16px; min-height: 1px; font-weight: normal; padding-right: 24px; margin: 0;" align="left" valign="top">
                        <h2 style="padding-top: 0; padding-bottom: 0; font-weight: 500; vertical-align: baseline; font-size: 32px; line-height: 38.4px; margin: 0;" align="left"> Uw gegevens:  </h2>
                        <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left">Bedrijfsnaam: ' . $cname . '</p>
                        <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left">Volledige naam: ' . $pname . '</p>
                      </td>
                      <td class="col" style="line-height: 24px; font-size: 16px; min-height: 1px; font-weight: normal; padding-right: 24px; margin: 0;" align="left" valign="top">
                        <h2 style="padding-top: 0; padding-bottom: 0; font-weight: 500; vertical-align: baseline; font-size: 32px; line-height: 38.4px; margin: 0;" align="left">Bandje gegevens: </h2>
                        <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left">Soort bandje: ' . $type_name . '</p>
                        <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left">Doel van het bandje: ' . $purpuse_bandje . '</p>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <br>
              <div class="row" style="margin-right: -24px;">
                <table class="" role="presentation" border="0" cellpadding="0" cellspacing="0" style="table-layout: fixed; width: 100%;" width="100%">
                  <tbody>
                    <tr>
                      <td class="col" style="line-height: 24px; font-size: 16px; min-height: 1px; font-weight: normal; padding-right: 24px; margin: 0;" align="left" valign="top">
                        <h2 style="padding-top: 0; padding-bottom: 0; font-weight: 500; vertical-align: baseline; font-size: 32px; line-height: 38.4px; margin: 0;" align="left"> Datum en tijden: </h2>
                        <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left">Start datum: ' . $startdate . '</p>
                        <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left">Start tijd: ' . $starttime . '</p>
                        <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left">Eind datum: ' . $enddate . '</p>
                        <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left">Eind tijd: ' . $endtime . '</p>
                      </td>
                      <td class="col" style="line-height: 24px; font-size: 16px; min-height: 1px; font-weight: normal; padding-right: 24px; margin: 0;" align="left" valign="top">
                        <h2 style="padding-top: 0; padding-bottom: 0; font-weight: 500; vertical-align: baseline; font-size: 32px; line-height: 38.4px; margin: 0;" align="left"> Overige gegevens: </h2>
                        <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left">Permanent bandje: ' . $permanent . '</p>
                        <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left">Keuze menu: ' . $keuzemenuValue . '</p>
                        <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left">Uitleg: ' . $explanation . '</p>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <br>
            </div>
            <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left">Met vriendelijke groet,</p>
            <p style="line-height: 24px; font-size: 16px; width: 100%; margin: 0;" align="left">Belbandjes.nl</p>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>
';

    //als de html mail niet werkt dan komt hier de plain text mail te staan
    $mail->AltBody = 'hier komt de mail te staan zoals je deze altijd wilt hebben in case dat html niet werkt bij de gebruiker.';
    $mail->send(); // verstuur de mail
    header("Location: " . $base_url . "/components/succes.php");

} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
