<?php
include("header.php");

$errorName = $errorEmail= $errorAge = $errorGender = $errorPass = $Reg ="";
$name = $hash = $email = $age = $gender = $password = $pic = "";

//Validation on user information
if ($_SERVER["REQUEST_METHOD"] == "POST"){

//Email Validation
    if(empty($_POST["name"])){
        $errorName = "name is required";

    }else{
        if (!preg_match("/^[a-zA-Z-' ]*$/",$_POST["name"])) {
            $errorName = "name can contain only Letters and Spaces";

        }
        $name = $_POST["name"];

    }

    //Only One user per email can be registered
    $users = json_decode(file_get_contents("userinfo.json"),true);
    if(empty($_POST["email"])){
        $errorEmail= "Email is required";
    }else{
        if (!filter_var($_POST["email"])) {
            $errorEmail = "Invalid format of email";
        }else{
            if(filesize("userinfo.json") == 0) {
                $users = json_decode(file_get_contents("userinfo.json"), true);
                foreach ($users as $user){
                    if ($user['email'] == $_POST["email"]) ;
                    {
                        $errorEmail = "Email is already registered";
                    }
                }
            }
        }
        $email = $_POST["email"];
    }

//    Age Validation
    if(empty($_POST["Age"])){
        $errorAge = "Age is required";

    }else{
        $age = $_POST["Age"];
    }

//    Gender Validation
    if(empty($_POST["gender"])){
        $errorGender = "Choose One";

    }else{
        $gender = $_POST["gender"];
    }

//    Password Validation
    if(empty($_POST["pass"])){
        $errorPass= "Set a strong Password";

    }else{
//      1.  Saves Encrypted Password
        $password = $_POST["pass"];
        $hash = password_hash($password,PASSWORD_DEFAULT);
    }

//    Saving profile image uploaded by user in a folder
    $allow = array("jpg", "jpeg", "gif", "png","jfif");
    $folder = './images/';
    if ( !!$_FILES['image']['tmp_name'] )
    {
        $info = explode('.', strtolower( $_FILES['image']['name']) );
        if ( in_array( end($info), $allow) )
        {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $folder . basename($_FILES['image']['name'] ) ) )
            {
                $pic = basename($_FILES['image']['name'] );
            }else
            {
                $pic = 'default.jpg';
            }
        }else
        {
            $pic = 'default.jpg';
        }
    }else
    {
        $pic = 'default.jpg';
    }
}

    if (isset($_POST["submit"])){

//saves User Information to userinfo.json
       $newUser = array("name"=>$name,"email"=>$email,"age"=>$age,"gender"=>$gender, "pic" => $pic, "pass"=>$hash);
        if(filesize("userinfo.json") == 0){
            $firstUser = array($newUser);
            $temp = $firstUser;
        }else{
            $oldUsers = json_decode(file_get_contents("userinfo.json"));
            array_push($oldUsers,$newUser);
            $temp = $oldUsers;
        }
        $emailErr = "";
        if(!file_put_contents("userinfo.json",json_encode($temp))){
            $Reg = "Error in Registration!!!";

        }else {
            $Reg = "Successfully Registered!!!";  //Success
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
        <h2>Register</h2>
        <div class="form-group"> Enter your name: 
            <input type="text" class="form-control" name="name" placeholder="name">
            <span class="error"><?php echo $errorName;?></span><br>
        </div>
        <div class="form-group"> Enter your email: 
            <input type="email" class="form-control" name="email" placeholder="Email">
            <span class="error"> <?php echo $errorEmail;
                $errorEmail = "";
            ?></span> <br>
        </div>
        <div class="form-group"> Enter your Age: 
                <input  type="number" class="form-control" name="Age" placeholder="Age">
                <span class="error"> <?php echo $errorAge;?></span><br>
            </div>
            <div> Select your gender:    
                    <label class="radio-inline me-3"><input type="radio" name="Gender" <?php if (isset($gender) && $gender=="male");?> value="male"> Male</label>
                    <label class="radio-inline me-3"><input type="radio" name="Gender" <?php if (isset($gender) && $gender=="female");?> value="female"> Female</label>
                    <span class="error"> <?php echo $errorGender;?></span><br>
            </div>
            
        </div>
        <div class="form-group"> Enter your password: 
            <input type="password" class="form-control" name="password" placeholder="Password">
            <span class="error"> <?php echo $errorPass;?></span><br>
        </div>
        <div class="form-group">
           <label>Profile Picture</label><input type="file" name="image" id="image">
        </div>
        <div style="margin-top: 15px" class="d-grid gap-2 d-md-block">
            <button class="btn btn-primary" type="submit" name="submit">Register</button>
        </div>
        <div style="margin-top: 15px" class="text-center">Already have an account? <a href="LogIn.php">Sign in</a></div>
        <div style="color: black">
        </div>

        <!-- Shows that user is registered or not -->
        <div>
            <?php
                echo $Reg;
                $Reg = "";
            ?>
        </div>
    </form>
</div>
</body>
</html>
