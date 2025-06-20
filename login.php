<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: home.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="container">
        <?php
        if (isset($_POST["submit"])) {
            $fullname = $_POST["fullname"];
            $emailid = $_POST["email"];
            $password = $_POST["password"];
            $confirm_password = $_POST["confirm_password"];

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $errors = array();
            if (empty($fullname) or empty($fullname) or empty($fullname) or empty($fullname)) {
                array_push($errors, "all fields are required");
            }
            if (!filter_var($emailid, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "email is not valid");
            }
            if (strlen($password) < 8) {
                array_push($errors, "password must be atleast 8 characters long");
            }
            if ($password != $confirm_password) {
                array_push($errors, "passwords are not matching");
            }
            $sql = "SELECT * FROM users WHERE email = '$emailid'";
            require_once "database.php";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount > 0) {
                array_push($errors, "Email already exists");
            }
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                $sql = "INSERT INTO users (full_name,email,password) VALUES (? , ? , ?)";
                $stmt = mysqli_stmt_init($conn);

                $prepare_stmt = mysqli_stmt_prepare($stmt, $sql);
                if ($prepare_stmt) {
                    mysqli_stmt_bind_param($stmt, "sss", $fullname, $emailid, $passwordHash);
                    mysqli_stmt_execute($stmt);
                    echo "<div class='alert alert-success'>You are registered successfully</div>";
                } else {
                    die("something went wrong");
                }
            }
        }
        ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="enter your name">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="enter your email">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="create a password">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="confirm_password" placeholder="confirm password">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="register" name="submit">
            </div>
        </form>
        <div>
            <p>Already registered<a href="register.php">Login Here</a></p>
        </div>
    </div>
</body>

</html>