<?php
// Import the PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$action = $_POST['action'];

// Functions -------------------------------------------------------------------------------------------------------------------------------------------------------------------
// (Log the action to a database table)
// Define a function for logging activity with a specified log level
function logActivity($conn, $log_level)
{
	try {
		// Get the visitor's IP address and current date/time
		$visitor_ip = $_SERVER['REMOTE_ADDR'];
		$datetime = date('Y-m-d H:i:s');

		// Query to retrieve the logging level name based on the log level
		$sql = "SELECT logging_level_name FROM log_levels WHERE Id = ?";
		// Prepare the SQL statement
		$stmt = $conn->prepare($sql);
		// Execute the SQL statement with the log level as a parameter
		$stmt->execute([$log_level]);
		// Fetch the result as an associative array
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		// Check if a result was found
		if ($result) {
			// Extract the log message from the result
			$log_message = $result['logging_level_name'];
			// SQL query to insert a log record into the 'logs' table, SQL statement, and execute the SQL statement with the gathered information
			$sql = "INSERT INTO logs (log_ip, log_timestamp, log_level, log_message) VALUES (?, ?, ?, ?)";
			$stmt = $conn->prepare($sql);
			$stmt->execute([$visitor_ip, $datetime, $log_level, $log_message]);
		} else {
			// Handle the case where no log level name was found
			echo "No log level name was found.";
		}
	} catch (PDOException $e) {
		// Handle any exceptions that might occur during database operations
		echo "Error: " . $e->getMessage();
	}
}

// CREATE ----------------------------------------------------------------------------------------------------------------------------------------------------------------------
// (Create an appointment, Log the action to a database table, Redirect to a confirmation page that displays information about the appointment)
if ($action == 'create') {
	// Start a PHP session
	session_start();

	// Retrieve data from the POST request

	$name = $_POST['fname'];
	$surname = $_POST['lname'];
	$phone = $_POST['phone'];
	$email = $_POST['mail'];
	$service = $_POST['dienst'];
	$date = $_POST['date'];
	$time = $_POST['time'];

	// validate the data:
	// --- Check if the 'name' field is empty
	if (empty($name)) {
		$error[] = "Vul een geldige naam in";
		header("Location: ../index.php?error=geen geldige voornaam");
	}

	// --- Check if the 'surname' field is empty
	if (empty($surname)) {
		$errors[] = "Vul een geldige naam in";
		header("Location: ../index.php?error=geen geldige achternaam");
	}

	// --- Check if the 'phone' field contains a numeric value
	if (!is_numeric($phone)) {
		$errors[] = "Vul een geldige telefoon nummer in";
		header("Location: ../index.php?error=geen geldige telefoon nummer");
	}

	// --- Check if the 'email' field is empty and validate its format
	if (empty($email)) {
		$emailErr = "Email is required";
	} else {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$emailErr = "Invalid email format";
		}
	}

	// --- Check if the 'service' field is not set
	if (!isset($_POST["service"])) {
		$dienstErr = "de dienst moet worden ingevuld";
	}

	// --- Check if the 'date' field is in the future
	if (strtotime($date) <= strtotime('today')) {
		$error[] = "Vul een geldige datum in";
		header("Location: ../index.php?error=geen geldige datum, datum moet in de toekomst liggen");
	}

	// --- Check if the 'time' field is empty
	if (empty($time)) {
		$error[] = "Vul een geldige tijd in";
		header("Location: ../index.php?error=geen geldige tijd");
	}

	// --- Check if there are any errors, and if so, dump the errors and terminate the script
	if (isset($errors)) {
		var_dump($errors);
		die();
	}

	// Establish a database connection
	require_once 'conn.php';
	// Define a SQL query to insert the appointment data, Prepare the SQL statement and Execute the SQL statement with the provided data
	$query = "INSERT INTO gebruikersgegevens (afspraak_voornaam, afspraak_achternaam, afspraak_telefoon_nummer, afspraak_email , afspraak_dienst, afspraak_date, afspraak_time) VALUES (:fname, :lname, :phone, :mail , :dienst, :date, :time)";
	$statement = $conn->prepare($query);
	$statement->execute([
		":fname" => $name,
		":lname" => $surname,
		":phone" => $phone,
		":mail" => $email,
		":dienst" => $service,
		":date" => $date,
		":time" => $time,
	]);
	// Retrieve the ID of the last inserted record
	$lastInsertedId = $conn->lastInsertId();

	// Set the level of logging for the table
	$log_level = 4;
	// Log the action to the database (through a function)
	logActivity($conn, $log_level);

	// Send an email to the user with the appointment details (through another php file)

	// Redirect the user to a confirmation page, with the last inserted id, with a success message and the data of the appointment
	header("Location: ../task/redirect.php?id=$lastInsertedId&message=afspraak aangemaakt");
	require('phpMailer.php');
}

// UPDATE ----------------------------------------------------------------------------------------------------------------------------------------------------------------------
// (Update an appointment, Log the action to a database table, Redirect back and display a confirmation message)
if ($action == 'edit') {
	// Start a PHP session and Set an error message in the session variable
	session_start();
	$_SESSION['nameErr'] = "dit is geen geldige naam!";

	// Retrieve data from the POST request
	$id = $_POST['id'];
	$name = $_POST['fname'];
	$surname = $_POST['lname'];
	$phone = $_POST['phone'];
	$email = $_POST['mail'];
	$service = $_POST['dienst'];
	$date = $_POST['date'];
	$time = $_POST['time'];

	// validate the data:
	// --- Check if the 'name' field is empty
	if (empty($name)) {
		$error[] = "Vul een geldige naam in";
		header("Location: ../index.php?error=geen geldige voornaam");
	}

	// --- Check if the 'surname' field is empty
	if (empty($surname)) {
		$errors[] = "Vul een geldige naam in";
		header("Location: ../index.php?error=geen geldige achternaam");
	}

	// --- Check if the 'phone' field contains a numeric value
	if (!is_numeric($phone)) {
		$errors[] = "Vul een geldige telefoon nummer in";
		header("Location: ../index.php?error=geen geldige telefoon nummer");
	}

	// --- Check if the 'email' field is empty and validate its format
	if (empty($email)) {
		$emailErr = "Email is required";
	} else {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$emailErr = "Invalid email format";
		}
	}

	// --- Check if the 'service' field is not set
	if (!isset($_POST["service"])) {
		$dienstErr = "de dienst moet worden ingevuld";
	}

	// --- Check if the 'date' field is in the future
	if (strtotime($date) <= strtotime('today')) {
		$error[] = "Vul een geldige datum in";
		header("Location: ../index.php?error=geen geldige datum, datum moet in de toekomst liggen");
	}

	// --- Check if the 'time' field is empty
	if (empty($time)) {
		$error[] = "Vul een geldige tijd in";
		header("Location: ../index.php?error=geen geldige tijd");
	}

	// --- Check if there are any errors, and if so, dump the errors and terminate the script
	if (isset($errors)) {
		var_dump($errors);
		die();
	}

	//establish a database connection
	require_once 'conn.php';
	// Define a SQL query to update the appointment data, Prepare the SQL statement and Execute the SQL statement with the provided data
	$query = "UPDATE afspraken SET afspraak_voornaam = :fname, afspraak_achternaam = :lname, afspraak_telefoon_nummer = :phone, afspraak_email = :mail, afspraak_dienst = :dienst, afspraak_date = :date, afspraak_time = :time  WHERE id = :id";
	$statement = $conn->prepare($query);
	$statement->execute([
		":fname" => $name,
		":lname" => $surname,
		":phone" => $phone,
		":mail" => $email,
		":dienst" => $service,
		":date" => $date,
		":time" => $time,
		":id" => $id,
	]);

	// Set the level of logging for the table and Log the action to the database (through a function)
	$log_level = 5;
	logActivity($conn, $log_level);

	// Redirect the user back with a success message
	header("Location: ../task/Editoverzicht.php?message=afspraak bewerkt");
}



// DELETE ----------------------------------------------------------------------------------------------------------------------------------------------------------------------
// (Delete an appointment, Log the action to a database table, Redirect back and display a confirmation message)
if ($action == 'delete') {
	// Retrieve the 'id' parameter from POST data
	$id = $_POST['id'];

	// Establish a database connection
	require_once 'conn.php';

	// Prepare and execute an SQL DELETE query to remove appointment data from the database
	$query = "DELETE FROM afspraken WHERE id = :id";
	$statement = $conn->prepare($query);
	$statement->execute([
		":id" => $id
	]);

	// Set the level of logging for the table and Log the action to the database (through a function)
	$log_level = 6;
	logActivity($conn, $log_level);

	// Redirect the user back with a success message
	header("Location: ../task/Editoverzicht.php?message=afspraak verwijdert");
}
