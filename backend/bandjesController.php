<?php
require_once("conn.php");

// Import the PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$action = $_POST['action'];

    if($action == "create"){
        
        // variables
        $cname                  = $_POST['c_name'];
        $pname                  = $_POST['p_name'];
        $tbandje                = $_POST['t_bandje'];
        $purpuse_bandje         = $_POST['purpuse_bandje'];
        

        //start date and time
        $startdate              = $_POST['start_date'];
        $starttimehour          = $_POST['hour'];
        $starttimeminute        = $_POST['minute'];
        $starttime              = $starttimehour . ":" . $starttimeminute;

        //end date and time 
        $enddate                = 0000-00-00;
        $enddatehour            = $_POST['hour2'];
        $endtimeminute          = $_POST['minute2'];
        $endtime                = $enddatehour . ":" . $endtimeminute;
        
        $keuzemenuValue         = $_POST['keuzemenu_value'];
        $explanation            = $_POST['menu_explanation'];
        $audioData              = $_POST['audioFile'];
        $permanent              = 0;
        
        //get current date and time
        $datetimenow    = date("d-m-y_H-i-s");
        
        $decodedAudioData       = base64_decode($audioData);
        
        $destinationDirectory   = '../audio_bestanden/';
        
        // Create a unique filename for the audio file  
        $convertedFilename      = "{$cname}-time-{$datetimenow}.wav";
        $convertedPath          = $destinationDirectory . $convertedFilename;
        
        // Create a temporary WAV file
        $tempWavFile            = tempnam(sys_get_temp_dir(), 'temp_audio_');

        file_put_contents($tempWavFile, $decodedAudioData);
        
        // FFmpeg command to convert the temporary WAV file
        $ffmpegCommand          = "ffmpeg -i \"$tempWavFile\" -acodec pcm_s16le -ar 44100 \"$convertedPath\"";
        
        // Execute the FFmpeg command
        
        try{
            shell_exec($ffmpegCommand);   
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        // Remove the temporary WAV file
        unlink($tempWavFile);
        
        // check if the checkbox is checked
        if (isset($_POST['permanent_bandje'])) {
            $permanent = 1;
        }

        // validate the data separately on data type and format
        // Check if the 'cname' field is empty
        if (empty($cname)) {
            $error[] = "Vul een geldige bedrijfs naam in";
        }
        if (empty($pname)) {
            $error[] = "Vul een geldige volledige naam in";
        }
        if (empty($tbandje)) {
            $error[] = "Maak een geldige keuze";
        }
        if($tbandje == "overig"){
            if(!isset($_POST['purpuse_bandje'] )){
                $error[] = "vul een doel van uw bandje in";
            } 
        }else {
            $_POST['purpuse_bandje'] = null;
        }
        if (empty($starttime)) {
            $error[] = "Vul een geldige start tijd in, die in de toekomst in";
        }

        if (empty($startdate) ) {
            $error[] = "Vul een geldige start datum in die in de toekomst ligt";
        }

        if(isset($_POST['permanent_bandje'])){
            $enddate = NULL;
            
            if (empty($endtime) || new DateTime($endtime) >= new DateTime()) {
                if (empty($endtime) || new DateTime($enddate . ' ' . $endtime) <= new DateTime()) {
                $error[] = "Vul een geldige eind tijd in, die in de toekomst in";
                }
            }
        } else{
            $enddate = $_POST['eind_date'];
        }

        //validation for the checkboxes
        if($keuzemenuValue != 0 ) {
            if (empty($explanation)) {
                $error[] = "Vul een geldige uitleg in";
            }
            if ($keuzemenuValue == 1) {
                $keuzemenuValue = 1;
            }
            else{
                $keuzemenuValue = 0;
            }
        }
        // check if the checkbox is checked
        if (isset($_POST['permanent_bandje'])) {
            $permanent = 1;
        }
        $klant_nr = null;
        
        // if (empty($error)) {
            
            // Prepare the query
            $query = "INSERT INTO gebruikersgegevens (bedrijfs_naam, volledige_naam, type_bandjes, purpuse_bandje, start_datum, start_tijd, eind_datum, eind_tijd, permanent_bandje, keuze_menu, uitleg, audio_location, audio_file, klant_nr) VALUES (:c_name, :p_name, :t_bandje, :purpuse_bandje, :start_date, :start_time, :eind_date, :eind_tijd, :permanent_bandje, :keuzemenu_value, :menu_explanation, :audio_location, :audio_file, :klant_nr)";
            $stmt = $conn->prepare($query);
            
            //Bind parameters (ask to bas)
            $stmt->bindParam(':c_name', $cname);
            $stmt->bindParam(':p_name', $pname);
            $stmt->bindParam(':t_bandje', $tbandje);
            $stmt->bindParam(':purpuse_bandje', $purpuse_bandje);
            $stmt->bindParam(':start_date', $startdate);
            $stmt->bindParam(':start_time', $starttime);
            $stmt->bindParam(':eind_date', $enddate);
            $stmt->bindParam(':eind_tijd', $endtime);
            $stmt->bindParam(':permanent_bandje', $permanent);
            $stmt->bindParam(':keuzemenu_value', $keuzemenuValue);
            $stmt->bindParam(':menu_explanation', $explanation);
            $stmt->bindParam(':audio_location', $destinationDirectory);
            $stmt->bindParam(':audio_file', $convertedFilename);
            $stmt->bindParam(':klant_nr', $klant_nr);
            
            $result = $stmt->execute();
           
            // here error will show 
            // error that page doesnt work; http 500 server error 
            // voeg de nieuwe tabel toe aan de query. gaat over de klanten id
            /*
            

   ! External Dependencies: If the shell_exec function (used to run FFmpeg) is not available or properly configured on your server, it can result in an error. Make sure FFmpeg is correctly installed and accessible to the PHP process.


File System Permissions: Make sure that your PHP script has the necessary permissions to read and write to the specified directories. If not, it can lead to issues with file operations.

PHP Version Compatibility: Check the PHP version on your server. Some functions and features may vary between PHP versions. Ensure that the version you are using is compatible with the code.

HTTP 500 Logs: Review the server logs to get more information about the specific error. The error message can provide more details about what went wrong.

To diagnose and fix the issue, you should:

Check server logs for more specific error messages.
Verify your database connection and SQL queries.
Confirm that FFmpeg is properly installed and accessible.
Improve exception handling to log or display detailed error messages.
Ensure file system permissions are set correctly.
Double-check the PHP version compatibility.
By addressing these potential issues, you can troubleshoot and fix the HTTP 500 error in your PHP code.

*/


            
            if ($result) {
                // Redirect to the homepage on success
                require_once("phpMailer.php");
                
            } else {
                // If the query is not successful, show an error
                echo "Error executing query: " . $stmt->errorInfo()[2];
            }
        } else {
            // Handle errors, e.g., display them to the user or log them
            foreach ($error as $errorMsg) {
                echo $errorMsg . '<br>';
            }
        }
    // }