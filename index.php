<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" >
        <title>Registration Form</title>
        <style>
            .error{color:red;}
        </style>
    </head>
    <body>

        <?php

        session_start();
        // step 1 declare vars

            $name = $surname = $idNumber = $dateOfBirth = "";
            $nameErr = $surnameErr = $idNumberErr = $dateOfBirthErr = "";
            $inputData = array();

        // step 2 write functions

            function input_test($data){
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

        // step 3 
        

            if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])){
                $conn = mysqli_connect("localhost","root","","test1") or die("Connection Error:" .mysqli_connect_error());
    
        // step 4 

                if(empty($_POST["name"])){
                    $nameErr = "Please insert your name";
                }else{
                    $name = input_test($_POST["name"]);
                    if(!preg_match("/^[a-zA-Z-' ]*$/",$name)){
                        $nameErr = "Name only allowed to have letters or whitespace";
                    }
                }

                if(empty($_POST["surname"])){
                    $surnameErr = "Please insert your surname";
                }else{
                    $surname = input_test($_POST["surname"]);
                    if(!preg_match("/^[a-zA-Z-' ]*$/",$surname)){
                        $surnameErr = "Surname only allowed to have letters or whitespace";
                    }
                }

                if(empty($_POST["idNumber"])){
                    $idNumberErr = "Please insert your ID number";
                }elseif(strlen($_POST["idNumber"]) < strlen("theStringIs13")){
                    $idNumberErr = "Your id nr is too short, please try again";
                }else{
                    $idNumber = input_test($_POST["idNumber"]);
                    $query = "SELECT * FROM users6 WHERE idNumber = '$idNumber' ";
                    $result = mysqli_query($conn,$query);
                    if(mysqli_num_rows($result) > 0){
                        $idNumberErr = "Your id already exists. Please try again";
                    }
                }

                if(empty($_POST["dateOfBirth"])){
                    $dateOfBirthErr = "Please insert a date of birth";
                }else{
                    $dateOfBirth = input_test($_POST["dateOfBirth"]);
                    $format = "d/m/Y";
                    if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dateOfBirth) && DateTime::createFromFormat($format, $dateOfBirth)) {
                        // Valid date format
                        $dateOfBirth = DateTime::createFromFormat($format, $dateOfBirth)->format('d-m-Y');
                    } else {
                        // Invalid date format
                        $dateOfBirthErr = "Please enter a valid date in the format: dd/mm/YYYY";
                    }
                }

            //step 5

            if($nameErr || $surnameErr || $idNumberErr || $dateOfBirthErr){
                echo "Error in fields. Please correct them before submitting.";
            }else{
                  $inputData = array(
                    "name" => $name,
                    "surname" => $surname,
                    "idNumber" => $idNumber,
                    "dateOfBirth" => $dateOfBirth,
                  );
                  $_SESSION['inputData'] = $inputData;

                $sql = "INSERT INTO `users6`(`name`,`surname`,`idNumber`,`dateOfBirth`) VALUES('$name','$surname','$idNumber','$dateOfBirth')";

                //step 6

                $query = mysqli_query($conn,$sql);

                if(!$query){
                    $error = mysqli_error($conn);
                    echo "Error Occurred: " . $error;
                    echo "<br> <br>";
                    echo "Unuccessfull";
                }else{
                    echo "Successfull";
                }
             }
        }

    ?>

<p><span class="error">*required field</span></p>
<br> <br>

<form method="POST" action= "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >

    <label>Name:</label>
    <input type="text" name="name" id="name" value=<?php echo isset($_SESSION['inputData']['name']) ? htmlspecialchars($_SESSION['inputData']['name']) : ''; ?>>
    <span class="error"><?php echo $nameErr ?> </span>
    <br><br>

    <label>Surname:</label>
    <input type="text" name="surname" id="surname" value= <?php echo isset($_SESSION['inputData']['surname']) ? htmlspecialchars($_SESSION['inputData']['surname']) : '';?>>
    <span class="error"><?php echo $surnameErr ?></span>
    <br><br>

    <label>ID Number:</label>
    <input type="number" name="idNumber" id="idNumber" value=<?php echo isset($_SESSION['inputData']['idNumber']) ? htmlspecialchars($_SESSION['inputData']['idNumber']) : '';?>>
    <span class="error"><?php echo $idNumberErr?></span>
    <br><br>

    <label>Date of Birth:</label>
    <input type="text" name="dateOfBirth" id="dateOfBirth" value=<?php echo isset($_SESSION['inputData']['dateOfBirth']) ? htmlspecialchars($_SESSION['inputData']['dateOfBirth']) : '';?>>
    <span class="error"><?php echo $dateOfBirthErr?></span>
    <br><br>

    <input type="submit" name="submit" id="submit" value="submit">
    <input type="button" name="cancel" id="cancel" value="cancel" onclick="cancelForm()">


</form>

<?php
    echo "<h2>Your Input is as follows if successfull :</h2>";
    echo $name;
    echo "<br>";
    echo $surname;
    echo "<br>";
    echo $idNumber;
    echo "<br>";
    echo $dateOfBirth;
   
?>

      <script>
            function cancelForm() {
                // Redirect to the same page
                window.location.href = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>";
        }
        </script>

    </body>
</html>