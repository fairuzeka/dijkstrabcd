<?php

session_start();
include 'connect.php';
$error='';


    if (isset($_POST['submit'])) {
    if ($opq=="in") {
        $nik=$_POST['user'];
        $pass=$_POST['pass'];    
        $opq=$_GET['opq'];
        $query = mysqli_query($con,"SELECT*FROM user2 where NIK='$nik' and password='$pass'");
        $rows = mysqli_num_rows($query);
        if ($rows==1) {//jika berhasil maka akan bernilai 1
        $abc=mysqli_fetch_array($query);
        $_SESSION['nik']=$abc['nik'];
        $_SESSION['levelus']=$abc['levelus'];
        $_SESSION['error']=$abc['error'];
        if ($abc['levelus']=="pegawai") {
            header("pegawai.php");
        }elseif ($abc['levelus']=="atasan") {
            header("atasan.php");
        }
        }else{
             $error="invalid Account";
        }
    }elseif ($opq=="out") {
        unset($_SESSION['nik']);
        unset($_SESSION['levelus']);
        header("location:index.php");
    }
}
?>
