<?php

include("header.php");

session_start();
$name = $_SESSION['name'];
$email = $_SESSION['email'];
$password = $_SESSION['pass'];
$age = $_SESSION['Age'];
$pic = $_SESSION['pic'];
$gender = $_SESSION['gender'];
$verify = false;
$hash = "";

//   logout Button
if (isset($_POST['logout'])) {
    session_start();
    session_unset();
    session_destroy();
    header('location: login.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //   Updating Name
    if (empty($_POST['uptName'])) {
        $errorName = 'Name is required';
    } else {
        if (!preg_match("/^[a-zA-Z-' ]*$/", $_POST['uptName'])) {
            $errorName = 'Name can contain only Letters and Spaces';
        }
        $name = $_POST['uptName'];
    }

    //    Updating Email
    if (empty($_POST['uptEmail'])) {
        $errorEmail = 'Email is required';
    } else {
        if (!filter_var($_POST['uptEmail'],FILTER_VALIDATE_EMAIL)) {
            $errorEmail = 'Invalid format of email';
        } else {
            $email = $_POST['uptEmail'];
        }
        if (empty($_POST['uptAge'])) {
            $errorAge = 'Age is required';
        } else {
            $age = $_POST['uptAge'];
        }
    }

   //   Updating Profile Picture

    if (isset($_POST['uptpic'])) {
        $pic = $_POST['uptpic'];
    }

    //    Updating Password

    if (!empty($_POST['uptPass'])) {

        //Saves Encrypted Password

        $password = $_POST['uptPass'];
        $hash = password_hash($password, PASSWORD_DEFAULT);

        //        Updating data in file

        $users = json_decode(file_get_contents('userdata.json'), true);
        foreach ($users as $key => $value) {
            if ($value['email'] == $_SESSION['email']) {
                $users[$key]['name'] = $name;
                $users[$key]['email'] = $email;
                $users[$key]['age'] = $age;
                $users[$key]['pic'] = $pic;
                $users[$key]['pass'] = $hash;
                $verify = true;
            }
        }
        $temp = $users;
    }
}
?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>
  </head>
  <body>
  <form method="post" action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF']); ?> ">
      <div class="container py-5">
         
            <h1 style="text-align: center; color: green;">Welcome <?php echo ucfirst($name); ?></h1>
         
         <div>
            <img src= "images/<?php echo $pic; ?>"; class="rounded-circle img-fluid" style="width: 100px;">
            <h5 class="my-0">
            <input type="file" style="margin-top: 1%; margin-left: 9%;" name="uptpic" id="uptpic">
            </h5>
         </div>
         <div>
            <p class="mb-0">Full Name</p>
               <div class="col-sm-9">
               <input type="text" class="form-control" name="uptName" value="<?php echo $name; ?>">
               </div>
         </div>
         <hr>
         <div>
            <p class="mb-0">Email</p>
            <input type="text" class="form-control" name="uptEmail" value="<?php echo $email; ?>">
         </div>
         <hr>
         <div>
            <p class="mb-0">Age</p>
            <input type="number" class="form-control" name="uptAge" value="<?php echo $age; ?>">
         </div>
         <hr>
         <div>
            <p>Gender</p>
         <p><?php echo $gender; ?></p>
         </div>
         <hr>
         <div>
            <p>New Password</p>
            <input type="password" class="form-control" name="uptPass" placeholder="Enter New Password">
         </div>
         <hr>
         <div>
            <button  class="btn btn-primary" type="submit" name="Save">Save Changes</button>
            <button  class="btn btn-primary" type="submit" name="logout">Log Out</button>
         </div>

         <?php if ($verify == true) {
            file_put_contents('userdata.json', json_encode($temp));
             //Cookies that saves User Authentication Token
             setcookie('email', $email, time() + 86400, '/');
             //Session Storage
             $_SESSION['email'] = $email;
             $_SESSION['Name'] = $name;
             $_SESSION['Age'] = $age;
             $_SESSION['pic'] = $pic;
             echo "<p style='color: #5cb85c;margin-top: 5%'>Data Updated Successfully!!!</p>";
            } 
               else {
                   echo "<p style='color: black; margin-top: 5%'>No Such Data Found</p>";
               } ?>
            </form>
        </section>
    </body>
</html>
