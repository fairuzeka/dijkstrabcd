<?php
session_start();
include 'connect.php';
//error_reporting(0);

//cek session
if (!isset($_SESSION["login"])){
    header ("location:index.php");
    exit;
}

if (isset($_POST['submit'])){
    $nik=$_POST['nik'];
    $jenis=$_POST['jenis'];
    $keperluan=$_POST['keperluan'];
    $telp=$_POST['telp'];
    $hp=$_POST['hp'];
    $ket=$_POST['ket'];


    $result=mysqli_query($con, "INSERT INTO requests (nik, pemesanan, keperluan, telp, hp, ket) VALUES ('$nik', '$jenis', '$keperluan', '$telp', '$hp', '$ket')");
    var_dump($result);
	if($result)
	{
		$last_id = mysqli_insert_id($con);
?>
		<script> 
			window.location = "jarak.php?id=<?php echo $last_id ?>";
		</script>
<?php
	} 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>KBM Online Pegawai</title>



	<!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>



<body>
	<!-- akhir menu navigasi -->
 <nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand glyphicon glyphicon-th-large" href="#">KBMOnline</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="active"><a href="Pegawai.php"> Home <span class="sr-only">(current)</span></a></li> 
      </ul>
    </div>
  </div>
</nav>
	<div class="container">			
		<!-- membuat jumbotron -->
		<div class="jumbotron">        
			<center>			
				<h2>Selamat datang di Form KBM Online</h2>
				<p>Silahkan isi form untuk peminjaman kendaraan</p><br/><br/>	
			</center>
		</div>
		<!-- akhir jumbotron -->
		<legend><div align="center"> -- Isi data diri dibawah -- </div></legend>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <form class="form-horizontal" method="POST" >
                    
                    <div class="form-group">
						<label for="disabledTextInput" class="col-md-4 control-label"> <div align="left"> NIK </div> </label>
						<div class="col-lg-8">
							<input type="text" readonly="true" class="form-control" value="<?php echo $_SESSION['nik']?>" id="nik" name="nik" />
						</div>
                    </div>
                    
                    
                    <div class="form-group">
                    <label for="jenis" class="col-md-4 control-label"> <div align="left"> Jenis Keperluan</div> </label> 
                    	<div class="col-lg-8">
                        	<select class="form-control" id="jenis" name="jenis">
                            <option value="Regular">Regular</option>
                            <option value="Sosial">Sosial</option>
                            <option value="Event">Event</option>
                            <option value="Emergency">Emergency</option>
                            <option value="Penanganan Gangguan">Penanganan Gangguan</option>
                            <option value="Direksi">Direksi</option>
                            </select>
                        </div>
                    </div>    


                    
                    <div class="form-group">
                    <label for="keperluan" class="col-md-4 control-label"> <div align="left"> Jenis Pemesanan </div> </label>
                        <div class="col-lg-8">
                            <select class="form-control" id="keperluan" name="keperluan">
                            <option value="Mobil">Mobil</option>
                            <option value="Mobil dan Sopir">Mobil dan Sopir</option>  
                            </select>
                      </div>
                     </div>
                     
                
                    <div class="form-group">
                    <label for="telp" class="col-md-4 control-label"> <div align="left"> No telepon kantor </div> </label>
                    	<div class="col-lg-8">
                        	<input type="text" class="form-control" id="telp" placeholder="No telepon kantor" name="telp">
                        </div>
                    </div> 
                    

                    <div class="form-group">
                    <label for="hp" class="col-md-4 control-label"> <div align="left"> No HP </div> </label>
                    	<div class="col-lg-8">
                        	<input type="text" class="form-control" id="hp" placeholder="No HP" name="hp">
                        </div>
                    </div> 


                    <div class="form-group">
                    <label for="ket" class="col-md-4 control-label"> <div align="left"> keterangan </div> </label>
                    	<div class="col-lg-8">
                        	<input type="text" class="form-control" id="ket" placeholder="keterangan" name="ket">
                        </div>
                    </div>

            

                     <div class="form-group">
                        <div class=" col-md-8 col-md-offset-3">
                            <input type="submit" class="btn btn-primary" name="submit" value="Submit" >
                            <input type="reset" class="btn btn-default" name="reset" value="Reset" >
                        </div>
                    </div>
                </div>
				</form>
            </div>
			<br><br><br>
        </div>
		<script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
	</body>
</html>