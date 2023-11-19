<?php
session_start();
if(isset($_SESSION["user"])){
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Registration</title>
</head>
<body>
        <div class="header1">
            <h1>REGISTRATION FORM</h1>
        </div>
        <div class="container">
        <?php
    if(isset($_POST["submit"]))
    {
        $fullName = $_POST['fullname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordRepeat = $_POST['repeat_password'];
        
        //to make the password decrypt and continues in line 66
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $errors = array();

        if(empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat))
        {
            array_push($errors, "All fields are required");
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            array_push($errors, "Email is not valid");
        }
        if(strlen($password)<8){
            array_push($errors, "Password must be atleast 8 characters long");
        }
        if($password!==$passwordRepeat){
            array_push($errors, "Password does not match");
        }

        require_once "database.php";
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        $rowCount = mysqli_num_rows($result);
        if($rowCount>0){
            array_push($errors, "Email already exists!");
        }

        if(count($errors)>0){
            foreach($errors as $error){
                echo "<div class='alert alert-danger'>$error</div>";
            }
        }else{
            $sql = "INSERT INTO users(full_name, email, password, visible_password) VALUES( ?, ?, ?, ? )";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
            if($prepareStmt){
                mysqli_stmt_bind_param($stmt,"ssss",$fullName, $email, $passwordHash, $passwordRepeat);//call the $passwordHash instead of $password to make the passwor decrypt
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>You are Registered Successfully...</div>";
            }else{
                die("Something went wrong");
            }
        }
    }
?>
            <form action="registration.php" method="POST">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Full Name" name="fullname">
                    <i class="bx bx-user"></i>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email" name="email">
                    <i class="bx bx-envelope"></i>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" name="password">
                    <i class="bx bx-lock-alt"></i>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Confirm Password" name="repeat_password">
                    <i class="bx bx-lock-alt"></i>
                </div>
                <div class="form-btn" id="form-atn">
                    <input type="submit" class="btn-pri" value="Register" name="submit">
                </div>
            </form>
            <div class="reg">
            <p>Already Registered <label><a href="login.php">Login Here</a></label></p>
            </div>
</div>
</body>
</html>
