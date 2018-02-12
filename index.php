<?php
$fnm = "";
$em = "";
$fle = "";
$dbl = "";
$eml = "";
$ftp = " ";


//extracting values from Get array

if (array_key_exists('error',$_GET)){$original_array = unserialize($_GET['error']);}
$fnm = (isset($original_array[0])) ? $original_array[0] : ' ';
$em = (isset($original_array[1])) ? $original_array[1] : ' ';
$fle = (isset($original_array[2])) ? $original_array[2] : ' ';
$eml = (isset($original_array[3])) ? $original_array[3] : ' ';
$ftp = (isset($original_array[4])) ? $original_array[4] : ' ';

$dbl = (isset($_GET['exists']) ) ? $_GET['exists'] :'';

?>

<!DOCTYPE HTML>
<!--
	The purpose of this file is to create contact records and file uploads
	to filebox using the infusionsoft api.
	-MGautier
-->
<head>
 <title> Create Contact</title>
 <meta charset="utf-8" />
<link rel="stylesheet" href="css/contact.css" />
</head>
<body>
<div id ="wrapper">

<form action='contacts.php' method='POST' enctype="multipart/form-data">
    <input type='hidden' name='returnURL' value='http://www.successengine.net'/>
     <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
      <input type='hidden' name='jad' value='testinput'>
    <fieldset>
      <legend id="legd"> Create contact </legend>
    <table>
        <tr>
            <td>FirstName:</td>
            <td><input type='text' name='Contact0FirstName'  />*</td>
            <td><span id="alert"><?php echo "$fnm"; ?>  </span>    </td>
        </tr>

        <tr>
            <td>LastName:</td>
            <td>
                <input type='text' name='Contact0LastName'  /><span> </td>
        </tr>
           <tr>
              <td>
                    <span id="dup"><?php echo "$dbl";  ?> </span>
              </td>
        </tr>
        <tr>
            <td>E-mail:</td>
            <td>
                <input type='text' name='Contact0Email'  />*</td>
                <td> <span id="alert"><?php echo "$em $eml";  ?> </td>
               
        </tr>
 
        <tr>
             <td> Upload Files*</td>
             <td>
                  <input type="file" name="UserFile" id="UserFile" accept="application/doc,application/docx,application/pdf"  multiple />
                
             </td>
             <td>
                   <span id="alert"><?php echo "$fle $ftp";  ?> </span>
              </td>  
             
             <tr>
             </tr>
             <tr>
             </tr>
       
        
        
            <td><input type='submit' value='Submit' id="submit"/></td>
        </tr>
    
    </table>
      
    </fieldset>
</form>




</div>
<!-- wrapper -->

</body>
</html>