<?php

session_start();
include 'connect.php';
$error='';

if(isset($_POST['submit'])){
    $nik=$_POST['user'];
    $pass=$_POST['pass'];
    $query = mysqli_query($con,"SELECT*FROM user2 where password='$pass' and NIK='$nik'");
    $rows = mysqli_num_rows($query);
     
    if ($rows==1){
  
    $get = mysqli_fetch_array($query);
    $level=$get['levelus'];
    $_SESSION['nik']=$nik;
    //cek session
    $_SESSION["login"]=true;
    
    if ($level=="atasan"){
        header("location:atasan.php");
    } elseif ($level=="pegawai") {
        header("location:pegawai.php");
    }
   }else{
       $error="invalid Account";
   }
}

?>





<!doctype html>

<html
    <head>
        <title>Login Page</title>
        
        <style>
            .login{
                width: 360px;
                margin: 50px auto;
                font: cambria, "hoefler text", "liberation serif", times, "times new roman",serif;
                border-radius: 10px;
                border: 5px solid #ccc;
                padding: 10px 40px 25px;
                margin-top: 78px;
            }
            input[type=text],input[type=password]{
                width: 99%;
                padding: 10px;
                margin-top: 8px;
                border: 1px solid #ccc;
                padding-left: 5px;
                font-size: 16px;
                font-family: cambria, "hoefler text", "liberation serif", times, "times new roman",serif;
                
            }
            input[type=submit]{
                width: 100px;
                background-color: #0099cc;
                border: 2px solid #06f;
                padding: 10px;
                font-size: 20px;
                color: #ffffff;
                cursor: pointer;
                border-radius: 5px;
                margin-bottom: 15px;
            }
            
        </style>
    </head>
    <body>
        <div class="login">
            <h1 align="center"><marquee>Selamat datang, silahkan login!</marquee></h1>
            <form method="POST" style="text-align: center;">          	
                <input type="text" placeholder="NIK" id="user" name="user"> </br></br>              
                <input type="password" placeholder="Password" id="pass" name="pass"> </br></br>
                <input type="submit" value="Login" name="submit"> 
                <span>
                    <?php echo $error;?>
                </span>
            </form>
        </div>
    </body>