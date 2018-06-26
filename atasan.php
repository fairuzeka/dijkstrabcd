<?php
session_start();

//cek session
if (!isset($_SESSION["login"])){
    header ("location:index.php");
    exit;
}

if (!isset($_SESSION["atasan"])){
    header ("location:index.php");
    exit;
}

include 'connect.php';
$requests = mysqli_query($con, "SELECT * FROM requests WHERE status IS NULL");

if (isset($_GET['ignore'])) {
  $result = mysqli_query($con, "UPDATE requests SET status = 0 WHERE id = '$_GET[ignore]'");
  if ($result) {
    header('location: atasan.php');
  }
}
if (isset($_GET['approve'])) {
  $result = mysqli_query($con, "UPDATE requests SET status = 0 WHERE id = '$_GET[approve]'");
  if ($result) {
    header('location: atasan.php');
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title> KBM Online Atasan</title>
        
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
	<!-- membuat menu navigasi -->
	<nav class="navbar navbar-default">
		<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand glyphicon glyphicon-th-large" href="" >KBMOnline</a>
			</div>
			
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				
				<ul class="nav navbar-nav navbar-right bg-danger">
                                    <li><a href="logout.php" >Logout</a></li>					
				</ul>
			
		</div><!-- /.container-fluid -->
	</nav>	
	<!-- akhir menu navigasi -->
    
 
	<div class="container">			
        <table class="table table-striped">
            <caption>List Approval</caption>
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">NIK</th>
      <th scope="col">Jenis Keperluan</th>
      <th scope="col">Jenis Pemesanan</th>
      <th scope="col">Telp. Kantor</th>
      <th scope="col">No. Hp</th>
      <th scope="col">Keterangan</th>
      <th scope="col">Waktu</th>
      <th scope="col">#</th>
    </tr>
  </thead>

  <tbody>
    <?php 
    $no = 1;
    while ($request = mysqli_fetch_array($requests)) { 
    ?>
    <tr>
      <th><?= $no++ ?></th>
      <td><?= $request['nik'] ?></td>
      <td><?= $request['pemesanan'] ?></td>
      <td><?= $request['keperluan'] ?></td>
      <td><?= $request['telp'] ?></td>
      <td><?= $request['hp'] ?></td>
      <td><?= $request['ket'] ?></td>
      <td><?= $request['created_at'] ?></td>
      <td>
        <a href="?approve=<?= $request['id'] ?>" class="btn btn-primary btn-xs">Approve</a>
        <a href="?ignore=<?= $request['id'] ?>" class="btn btn-danger btn-xs">Ignore</a>
        <a href="?detail=<?= $request['created_at'] ?>" class="btn btn-info btn-xs">Detail</a>
      </td>
    </tr>
    <?php } ?>
  </tbody>
</table>

<?php if (isset($_GET['detail'])) {

$details = mysqli_query($con, "SELECT * FROM detail WHERE created_at = '$_GET[detail]'");

} 
?>

<?php if (isset($_GET['detail'])): ?>
  <table class="table">
    <caption>Details View</caption>
  <thead>
    <tr>
      <th scope="col">Kota Asal</th>
      <th scope="col">Kota Tujuan</th>
      <th scope="col">Jarak</th>
      <th scope="col">Setimasi</th>
      <th scope="col">Bensin</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($detail = mysqli_fetch_array($details)) { ?>
      <tr>
        <td><?= $detail['asal'] ?></td>
        <td><?= $detail['tujuan'] ?></td>
        <td><?= $detail['jarak'] ?></td>
        <td><?= $detail['waktu'] ?></td>
        <td><?= $detail['bbm'] ?></td>
      </tr>
    <?php } ?>

  </tbody>
</table>
<?php endif ?>

		<script src="jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
	</body>
</html>