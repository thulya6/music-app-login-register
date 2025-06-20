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
    <title>Login form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="container">
        <?php
        if (isset($_POST["login"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];
            require_once "database.php";
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $res = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($res, MYSQLI_ASSOC);
            if ($user) {
                if (password_verify($password, $user["password"])) {
                    session_start();
                    $_SESSION["user"] = "yes";
                    header("Location: home.php");
                    die();
                } else {
                    echo "<div class = 'alert alert-danger'>Password does not match </div>";
                }
            } else {
                echo "<div class = 'alert alert-danger'>Email does not exist </div>";
            }
        }
        ?>
        <form action="register.php" method="post">
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="enter your email">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="enter your password">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="login" name="login">
            </div>
        </form>
        <div>
            <p>Not registered yet <a href="login.php">Register Here</a></p>
        </div>
    </div>
</body>

</html>