<?php
//include the SDK


//require_once("src/isdk.php");


// define variables and set to empty value

$firstname = "";
$firstnameErr = "";
$lastname  = "";
$email = "";
$emailErr = "";
$emailtypeErr = "";
$emailtest = "";
$fileErr = "";
$filetypeErr = "";
$files = "";
$filefailed = "";
$filefailedup = "";
$filefailedsz = "";

// check for posted values and validate

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["Contact0FirstName"])) {
		$firstnameErr = "First name is required";
	} else {
		$firstname = test_input($_POST["Contact0FirstName"]);
	}


	$lastname = test_input($_POST["Contact0LastName"]);
	//check for email input
	if (empty($_POST["Contact0Email"])) {
		$emailErr = "Email is required";
	} else {
		$emailtest = test_input($_POST["Contact0Email"]);
	}
	
	
	//check for valid email format
	if (filter_var($emailtest, FILTER_VALIDATE_EMAIL) === false) {
		$emailtypeErr = " Valid email address is required";
	} else {
		$email = $emailtest;
	}
	
	
	
	
	//check for uploaded files
	if(empty($_FILES['UserFile']['name'][0])) {
		$fileErr = "File upload is required";
	} else {
		$files = $_FILES['UserFile'];
		$uploaded = array();
		$failed = array();
		$allowed = array('doc','docx','pdf');
	

		 
	}
}
 
//tests data inputs

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;

}


$dataerrors = array ($firstnameErr,$emailErr,$fileErr,$emailtypeErr,$filetypeErr);

$dataerrors = array_filter($dataerrors);

if (!empty($dataerrors)) {
	 
	$frmerr = serialize($dataerrors);
	header("location:./?error=$frmerr");

	exit();
} else {

}



//build our application object
$app = new iSDK;

//connect to the API - change demo to be whatever your connectionName is!

if ($app->cfgCon("jad", "3464980971db56ef980086c880e64bf6"))  {


	//grab our posted contact fields
	$contact = array('Email' => $_POST['Contact0Email'],
			'FirstName' => $_POST['Contact0FirstName'],
			'LastName' => $_POST['Contact0LastName']);
	//grab the returnURL
	$returnURL = $_POST['returnURL'];

	//dup check on email if it exists.
	if (!empty($contact['Email'])) {
		//check for existing contact;
		$returnFields = array('Id');
		$dups = $app->findByEmail($contact['Email'], $returnFields);

		if (!empty($dups)) {
			//update contact
			$app->updateCon($dups[0]['Id'], $contact);
			//run an action set on the contact
			$app->runAS($dups[0]['Id'], $actionId);
		} else {
			//Add new contact
			$newCon = $app->addCon($contact);
			//run an action set on the contact

			$app->runAS($newCon, $actionId);
		}


	}

	// get user id for file upload

	$returnFields = array('Id');
	$actionId = $app->findByEmail($contact['Email'], $returnFields);
	$actionId = $actionId[0]['Id'];

	//if they uploaded a file add it the filebox to the contact record
	//if not quit and error out
	if(isset($_FILES['UserFile'])){

		//open the file
		$fileOpen = fopen($_FILES['UserFile']['tmp_name'], 'r');

		//read the data and save it to a variable
		$data = fread($fileOpen, filesize($_FILES['UserFile']['tmp_name']));

		//close the file
		fclose($fileOpen);

		//encode the data from the file in base 64
		//infusionsoft needs the data to be in this format to store it properly
		$dataEncoded = base64_encode($data);

		//upload file into app
		$uploadSuccess = $app->uploadFile($_FILES['UserFile']['name'], $dataEncoded, $actionId);
		//if the upload worked, display a success message
		//if it didn't, quit and error out
		if($uploadSuccess){
			#you can put whatever HTML code you want here
			#this is just there to let you know that it worked
			#after testing the upload, you can go login to your Infusionsoft account
			#pull up the contact record for the person, and look in their file box
			echo "File upload worked.<br>ContactId: " . $actionId .'<br>Upload Id: ' . $uploadSuccess;
		}
		sleep(10);
		//Send them to the success page
		header('location: ' . $returnURL);

	} else {
		//Let them know how it is ;)
		die('You must provide at least an email address.');
	}

} else {
	echo "Connection Error";
}


?>






















?>