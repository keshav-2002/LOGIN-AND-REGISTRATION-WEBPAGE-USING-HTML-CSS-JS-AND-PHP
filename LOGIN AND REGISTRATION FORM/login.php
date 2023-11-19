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
    <link rel="stylesheet" href="style1.css">
    <title>Login</title>
</head>
<body>
    <div class="header2">
        <h1>LOGIN FORM</h1>
    </div>
    <div class="container">

    <?php
    if(isset($_POST["login"]))
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        require_once "database.php";
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if($user){
            if(password_verify($password, $user["password"])){
                session_start();
                $_SESSION["user"] = "yes";
                header("Location: index.php");
                die();
            }else{
                echo "<div class='alert alert-danger'>Password does not exists</div>";    
            }
        }else{
            echo "<div class='alert alert-danger'>Email does not exists</div>";
        }
    }
    ?>
        <form action="login.php" method="POST">
            <div class="form-group">
                <input type="email" class="form-control" placeholder="Email" name="email">
                <i class="bx bx-envelope"></i>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Password" name="password">
                <i class="bx bx-lock-alt"></i>
            </div>
            <div class="form-btn" id="form-atn">
                <input type="submit" class="login" value="Login" name="login">
            </div>
        </form>
        <div class="reg">
            <p>Not Registered yet <label><a href="registration.php">Register Here</a></label></p>
        </div>
    </div>
</body>
</html>