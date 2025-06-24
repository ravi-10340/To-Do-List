<?php 
    session_start();
    if (isset($_POST['login'])) {
        $user = trim($_POST['username']);
        $pass = $_POST['pass'];
        $con = mysqli_connect("localhost", "root", "", "mypdb");
        $query = mysqli_query($con, "SELECT * FROM login WHERE email = '$user' OR mobile = '$user'");
        if (mysqli_num_rows($query) == 1) {
            $row = mysqli_fetch_assoc($query);
            if ($pass == $row['password']) {
                $_SESSION['userid'] = $row['id'];
                $_SESSION['username'] = $row['name'];
                header("Location: main.php");
                exit();
            } else {
                $login_error = "Incorrect password";
            }
        } else {
            $login_error = "Username not found";
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>To-do-list-login</title>
        <link rel="icon" href="image.png" type="image/png">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <style>
            *{
                margin:0px;
            }
            body{
                background:white;
                font-family:Arial;
                background-image: url("https://img.freepik.com/free-photo/overhead-view-tea-cup-eyeglasses-pencil-spiral-notepad-against-beige-backdrop_23-2147979070.jpg?semt=ais_hybrid&w=740");
                background-size: cover;          
                background-repeat: no-repeat;
                background-position: center;        
                background-attachment: fixed;       
            }
            .main {
                max-width: 350px;
                width: 90%;
                border: 1px solid;
                margin: 10%  0 0 10%;
                box-sizing: border-box;
                border-radius: 25px;
                box-shadow: 3px 3px 6px black;
                overflow: hidden;
                opacity:0.9;
                text-align: center;
                border: 0.5px solid; 
                background:#FDF0E3;  
            }
            .head{
                display: inline-block;
                background:black;
                height: 50px;
                width: 50%;
                box-sizing: border-box;
                color: white;
                line-height: 50px;
                font-weight: bold;
                float:left;
                color:white;
                border: none;
                border-bottom: 1px solid #ccc;
                border-radius: 0;
            }
            .head:hover {
                cursor: pointer;
                background:green;         
                color: black;              
                transform: scale(1.05);    
                box-shadow: 0 4px 10px rgba(215, 202, 202, 0.3);
                transition: all 0.3s ease;
            }
            .login {
                width: 90%;
                max-width: 350px;
                margin: 10% 0 0 0 ;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            h1 {
                font-size: 32px;
                text-align: center;
                margin-bottom: 20px;
            }
            input{
                height:30px;
                border-radius: 20px;
                background:#FFFFFF;
                color:black;
            }
            label, input {
                display: block;
                width: 80%;
                margin-left: auto;
                margin-right: auto;
                box-sizing: border-box;
            }
            label {
                font-size: 18px;
                font-weight: bold;
                margin-top: 10px;
                margin-bottom: 5px;
                text-align: left; 
            }
            button {
                display: block;
                width: 80%;
                margin: 10px auto;
                padding: 8px;
                border-radius: 20px;
                border: none;
                background:red;
                color: white;
                font-weight: bold;
            }
            button:hover {
                cursor: pointer;
                background:green;
                transform: scale(1.05); 
            }
            a{
                display: block;
                text-align: center;
                margin-top: 10px;
                font-size: 14px;
                color: #333;
                text-decoration: none;
            }
            a:hover {
                text-decoration: underline;
            }
            #l2{
                display:none;
            }
            @media screen and (max-width: 480px) {
                .main {
                    width: 95%;
                    margin: 20% auto;
                }
                .login {
                    padding: 15px;
                }
                input, button {
                    width: 90%;
                }
            }
        </style>
    </head>
    <body>    
        <div class = "main">
            <div class="head" id="btnL1">LOGIN</div>
            <div class="head" id="btnL2">SIGN UP</div>
            <br>
            <div class = "login" id = "l1">
                <p id="login-error" style="color:red; text-align:center; display:none;"></p>
                <form id="loginForm" action="login.php" method="post">
                    <label for="username">Username</label>
                    <input type="text" id="login-user" placeholder="Enter phone number or email" name = "username"/>
                    <label for="pass">Password</label>
                    <input type="password" id="login-pass" placeholder="Enter password " name = "pass"/>
                    <br>
                    <!-- <a href="#" id="fp">Forgot Password </a> -->
                    <button name = "login">Login</button>
                </form>
            <br>
            </div>
            <div class = "login" id = "l2">
                <form id="signupForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <label for="username">Name</label>
                    <input type="text" placeholder="Enter Name" name = "username"/>
                    <label for="email">Email</label>
                    <input type="text" placeholder="Enter email " name = "email"/>
                    <label for="mob">Phone No.</label>
                    <input type="tel" placeholder="Enter Phone No. " name = "mob"/>
                    <label for="pass">Password</label>
                    <input type="password" placeholder="Enter password " name = "pass"/>
                    <br>
                    <button name = "sign">Sign Up</button>
                </form>
                <?php
                    if (isset($_POST['sign'])) {
                        $user = trim($_POST['username']);
                        $pass = $_POST['pass'];
                        $mail = trim($_POST['email']);
                        $mob = trim($_POST['mob']);
                        $errors = [];
                        if (!$user || !$pass || !$mail || !$mob) $errors[] = "All fields are required.";
                        if (strlen($pass) < 8) $errors[] = "Password must be at least 8 characters long.";
                        if (!preg_match("/[A-Z]/", $pass)) $errors[] = "Password must contain at least one uppercase letter.";
                        if (!preg_match("/[0-9]/", $pass)) $errors[] = "Password must contain at least one number.";
                        if ($errors) {
                            foreach ($errors as $e) {
                                echo "<p style='color:red; text-align:center;'>$e</p>";
                            }
                        } else {
                            $con = mysqli_connect("localhost", "root", "", "mypdb") or die("Database connection failed");
                            $query = mysqli_query($con, "INSERT INTO login(name, mobile, email, password) VALUES('$user', '$mob', '$mail', '$pass')");
                            echo $query ? "<p style='color:green; text-align:center;'> </p>" : "<p style='color:red; text-align:center;'>Error in query.</p>";
                        }
                    }
                ?>
                <a href="login.php"> Already have account ? <u>Login</u></a>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                $('.main').hide().slideDown(800);
                $('#btnL2').click(function () {
                    $('#l1').fadeOut(300, function () {
                        $('#l2').fadeIn(300);
                    });
                });
                $('#btnL1').click(function () {
                    $('#l2').fadeOut(300, function () {
                        $('#l1').fadeIn(300);
                    });
                });
                $('form[action="login.php"]').submit(function (e) {
                    const username = $('#login-user').val().trim();
                    const password = $('#login-pass').val().trim();
                    if (username === '') {
                        if(password === ''){
                            e.preventDefault();
                            $('#login-error').text('Please enter both username and password.').show();
                        }else{
                            $('#login-error').text('Please enter username.').show();
                        }
                    }
                    else{
                        if(password === ''){
                            e.preventDefault();
                            $('#login-error').text('Please enter  password.').show();
                        }
                    }
                });
                // $('#signupForm').submit(function (e) {
                //     const username = $('input[name="username"]').val().trim();
                //     const email = $('input[name="email"]').val().trim();
                //     const phone = $('input[name="mob"]').val().trim();
                //     const pass = $('input[name="pass"]').val().trim();
                //     let errors = [];
                //     if (!username || !email || !phone || !pass) errors.push("All fields are required.");
                //     if (pass.length < 8) errors.push("Password must be at least 8 characters.");
                //     if (!/[A-Z]/.test(pass)) errors.push("Password must contain at least one uppercase letter.");
                //     if (!/[0-9]/.test(pass)) errors.push("Password must contain at least one number.");
                //     if (errors.length > 0) {
                //         e.preventDefault();
                //         alert(errors.join("\n"));
                //     }
                // });
            });
        </script>
    </body>
</html>
