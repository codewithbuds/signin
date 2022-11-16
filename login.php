<?php
include("header.php");

//If user is already login, take user to user profile page.
$errorEmail = $errorPass = $errLogin = "";
$name = $gender = $age = $email = $password = $pic = "";
if (!empty($_SESSION["email"])) {
    if (!empty($_SESSION["pass"])) {
        $users = json_decode(file_get_contents("userinfo.json"), true);
        $verify = False;
        foreach ($users as $user) {
            if ($user['email'] == $_SESSION["email"]) {
                if (password_verify($_SESSION["pass"], $user['pass'])) {
                    $name = $user['name'];
                    $age = $user['age'];
                    $pic = $user['pic'];
                    $email = $user['email'];
                    $password = $user['pass'];
                    $gender = $user['gender'];
                    $verify = True;
                    break;
                }
            }
        }
        if ($verify) {

//Cookies that saves User Authentication Token
            setcookie('email', $email, time() + 86400, '/');
            setcookie('pass', $password, time() + 86400, '/');
//Session Storage
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['pass'] = $password;
            $_SESSION['name'] = $name;
            $_SESSION['Age'] = $age;
            $_SESSION['Gender'] = $gender;
            $_SESSION['pic'] = $pic;
            header("location: home.php");
            exit();
        } else {
            $errLogin = "Failed To Login, Try Again";
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

// Email Validation
    if (empty($_POST["email"])) {
        $errorEmail = "Wrong Email Address";
    } else {
        if (!filter_var($_POST["email"])) {
            $emailErr = "Field Can't Be Empty";
        } else {
            $email = $_POST["email"];
        }
    }

//   Password Validation
    if (empty($_POST["pass"])) {
        $errorPass = "Field Can't Be Empty";
    } else {
        $password = $_POST["pass"];
    }

//   Compare Data With Stored Data
    if (isset($_POST["submit"])) {
        $users = json_decode(file_get_contents("userinfo.json"), true);
        $verify = False;
        foreach ($users as $user) {
            if ($user['email'] == $email) {
                if (password_verify($password, $user['pass'])) {
                    $name = $user['name'];
                    $age = $user['age'];
                    $pic = $user['pic'];
                    $gender = $user['gender'];
                    $verify = True;
                    break;
                }
            }
        }
        if ($verify == True) {
// Cookies that saves User Authentication Token
            setcookie('email', $email, time() + 86400, '/');
            setcookie('pass', $password, time() + 86400, '/');

//Session Storage
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['pass'] = $password;
            $_SESSION['name'] = $name;
            $_SESSION['age'] = $age;
            $_SESSION['gender'] = $gender;
            $_SESSION['pic'] = $pic;
            header("location: home.php");    //Success
            exit();
        } else {
            $errLogin = "Failed To Login, Try Again";  //Fail To Login
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Registration of User</title>
  <!-- <link rel="stylesheet" type="text/css" href="style.css"> -->
</head>
<body>
<div class="signup-form">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
                <h2>Log In</h2>
                <div  class="form-group">Enter your email:
                    <input type="email" class="form-control" name="Email" placeholder="Email" value="<?php echo $_COOKIE['email'] ?>">
                    <span class="error"><?php echo $errorEmail;?></span>
                </div>
                <div  class="form-group">Enter your Password:
                    <input type="password" class="form-control" name="password" placeholder="Enter Your Password" ">
                    <span class="error"><?php echo $errorPass;?></span>
                </div>
                <div>
                    <button class="btn btn-primary" type="submit" name="submit">Log In</button>
                </div>
                <div>New Member? <a href="signup.php">Register</a></div>
                <span class="error"><?php echo $errLogin;?></span>
            </form>
        </div>
    </body>
</html>