<?php
session_start();


//cek session
if (!isset($_SESSION["login"])){
    header ("location:index.php");
    exit;
}

include 'connect.php';
//error_reporting(0);

if (isset($_POST['submit'])){
    $id=$_POST['id'];
    $asal=$_POST['asal'];
    $tujuan=$_POST['tujuan'];
    $jarak=$_POST['jarak'];
    $waktu=$_POST['waktu'];
    $bbm=$_POST['bbm'];
    $created_at=$_POST['created_at'];

    $result=mysqli_query($con, "INSERT INTO detail (request_id, asal, tujuan, jarak, waktu, bbm, created_at) VALUES ('$id', '$asal', '$tujuan', '$jarak', '$waktu', '$bbm', '$created_at')");
    if ($result) {
    	header('location: /');
    }
}

?>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<meta charset="utf-8">
<style>
html, body, #map-canvas {
	width: 100%;
	height: 94%;
	margin: 3px;
	padding: 0px
}
a{
	cursor: pointer;
	text-decoration: underline;
}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfUutw5kI2URradTkzL1C409sqildXJNc&callback=initialize" async="" defer=""></script>
<script src="https://maps.googleapis.com/maps/api/distancematrix/json?origins=Vancouver+BC|Seattle&destinations=San+Francisco|Victoria+BC&mode=bicycling&language=fr-FR&key=AIzaSyDfUutw5kI2URradTkzL1C409sqildXJNc"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
// map
var poly = '';
var map;
var markeruser = {lat: -6.9940775, lng: 110.4218061};
var markerdestination = '';

// boolean
var __global_user		 = false;
var __global_destination = false;
var update_timeout;

// temporary list angkot
var temp_list_angkot = [];

/**
* INITIALIZE GOOGLE MAP
*/
function initialize() {	
	/* setup map */
	var mapOptions = {
		zoom: 10,
		center: new google.maps.LatLng(-6.9940775,110.4218061)
	};
	map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	
	/* create marker and line */
	var myLatLng = {lat: -6.9940775, lng: 110.4218061};
	var markeruser = new google.maps.Marker({
		position: myLatLng,
		map: map,
	});
	
}

/** 
* PILIH DESTINATION (SEKOLAH) VIA <SELECT>
*/
function choose_destination(value){
	// teks option
	var teks = $("#select_tujuan option:selected").text();
	
	// -- PILIH -- dipilih
	if(value == 'pilih') return false;
	
	// reset polyline
	if(poly != '') poly.setMap(null);
	
	// RESET ANGKOT SEBELUMNYA
	$(temp_list_angkot).each(function(w, x){
		// x = marker0, marker1 dst
		window[x].setMap(null);
	});			
	
	var location = JSON.parse(value);
	icons = 'http://latcoding.com/domains/dijkstra.latcoding.com/imgs/school_24.png';
	
	if(__global_destination == false){
		markerdestination = new google.maps.Marker({
			position: location,
			map: map,
			icon: icons,
			draggable: false,
			title: 'TUJUAN : ' + teks,
		});
		
		__global_destination = true;
	}else{
		markerdestination.setPosition(location);
		markerdestination.setTitle('TUJUAN : ' + teks);
	}
}

/**
* GET JSON DIJSKTRA VIA AJAX
*/
function send_dijkstra(){
	
	if(markeruser == '' || markerdestination == ''){
		alert('Isi dulu koordinat user & tujuan');
		return false;
	}
	
	now_koord_user 			= '{"lat":-6.9940775,"lng":110.4218061}';
	now_koord_destination 	= '{"lat": ' + markerdestination.position.lat() + ', "lng": ' + markerdestination.position.lng() + '}';

	// destination and duration
	var service = new google.maps.DistanceMatrixService;
	service.getDistanceMatrix({
		origins: [{lat: -6.9940775, lng: 110.4218061}],
		destinations: [{lat: markerdestination.position.lat(), lng: markerdestination.position.lng()}],
		travelMode: 'DRIVING',
		unitSystem: google.maps.UnitSystem.METRIC,
		avoidHighways: false,
		avoidTolls: false
	}, function (response, status) {
		for (var i = 0; i < response.originAddresses.length; i++) {
			var results = response.rows[i].elements
			for (var j = 0; j < results.length; j++) {
				$('#tujuan').val($('#select_tujuan option:selected').data('tujuan'))
				$('#jarak').val(results[j].distance.text)
				$('#waktu').val(results[j].duration.text)
				$('#bbm').val(Math.round((results[j].distance.value/10000)*8000))
			}
		}
	});

	// loading
	$('#run_dijkstra').hide();
	$('#loading').show();
	
	$.ajax({
		method:"POST",
		url : "Main.php",
		data: {koord_user: now_koord_user, koord_destination: now_koord_destination},
		success:function(response){
			
			// remove loading
			$('#run_dijkstra').show();
			$('#loading').hide();
						
			var json = JSON.parse(response);
			
			// RESET POLYLINE
			if(poly != '') poly.setMap(null);
			
			// RESET ANGKOT SEBELUMNYA
			$(temp_list_angkot).each(function(w, x){
				// x = marker0, marker1 dst
				window[x].setMap(null);
			});

			// ERROR ALGORITMA DIJKSTRA
			if(json.hasOwnProperty("error")) alert(json['error']['teks']);
			
			// GAMBAR JALUR SHORTEST PATH
			/* setup polyline */
			var polyOptions = {				
				/*path: [
				{"lat": 37.772, "lng": -122.214},
				{"lat": 21.291, "lng": -157.821},
				{"lat": -18.142, "lng": 178.431},
				{"lat": -27.467, "lng": 153.027}],
				*/
				path: json['jalur_shortest_path'],
				geodesic: true,
				strokeColor: 'rgb(20, 120, 218)',
				strokeOpacity: 1.0,
				strokeWeight: 2,
			};			
			poly = new google.maps.Polyline(polyOptions);
			poly.setMap(map);
			
			// GAMBAR KOORDINAT ANGKOT
			$(json['angkot']).each(function(i, v)
			{
				// no_angkot
				no_angkot = JSON.stringify(v['no_angkot']);
				window['infowindow'+i] = new google.maps.InfoWindow({
					content: '<div>'+ no_angkot +'</div>'
				});
				
				// koordinat angkot
				koordinat_angkot = v['koordinat_angkot'];
				window['marker'+i] = new google.maps.Marker({
					position: koordinat_angkot,
					map: map,
					title: 'title',
					icon: 'http://latcoding.com/free_download/implementasi_dijkstra_di_android/car.png'
				});
				
				// popup
				window['marker'+i].addListener('click', function() {
					window['infowindow'+i].open(map, window['marker'+i]);
				});
				
				// temporary list angkot
				temp_list_angkot[i] = 'marker'+i;
			});
		},
		error:function(er){
			alert('error: '+er);
			
			// remove loading
			$('#run_dijkstra').show();
			$('#loading').hide();
		}
	});	
}

/* load google maps v3 */
google.maps.event.addDomListener(window, 'load', initialize);
</script>

<script>
function sweetalertclick(){
swal("Berhasil diinput", "", "success");
}
</script>

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
	<!-- membuat menu navigasi -->
	<nav class="navbar navbar-default">
		<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
			
				<a class="navbar-brand glyphicon glyphicon-th-large" href="">KBMOnline</a>
			</div>
			
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="Pegawai.php"> Home <span class="sr-only">(current)</span></a></li>
                </ul>

				<ul class="nav navbar-nav navbar-right bg-danger">
                                    <li><a href="logout.php">Logout</a></li>					
				</ul>
			
		</div><!-- /.container-fluid -->
	</nav>	<!-- akhir menu navigasi -->
 
	<div class="container">			
		<!-- membuat jumbotron -->
		<div class="jumbotron">        
			<center>			
				<h2>Selamat datang di Form KBM Online</h2>
				<p>Silahkan isi form untuk peminjaman mobil</p><br/><br/>	
			</center>
		</div>
            <div class="row">
                <div class="col-md-6">
                    <div id="map-canvas" style="float:left;"></div>
                </div>
				</form>
                <div class="col-md-6">
					<div class="panel panel-info">
						<div class="panel-heading">
									<h2><i class="fa fa-car"></i> Hitung Jarak Tempuh</h2>
						</div>
						<div class="panel-body">
							</form>
							<div class="form-group">
							<label for="ket" class="col-md-4 control-label"> <div align="left"> Asal </div> </label>
								<div class="col-lg-8">
									<input type="text" class="form-control" id="ket" value="Telkom Semarang" placeholder="keterangan" name="ket" disabled>
								</div><br>
							</div>
							<div class="form-group">
								<label for="tujuan" class="col-md-4 control-label"> <div align="left"> Lokasi Tujuan </div> </label>
								<div class="col-lg-8">
									<select class="form-control" id="select_tujuan" onchange="choose_destination(this.value)">
										<option value="pilih">-- PILIH --</option>
<?php
include "Main.php";
$m = new Main();
$query 	= mysqli_query($con,"SELECT * FROM sekolah");
	while($fetcha = mysqli_fetch_array($query))
	{
		$koordinat 		= $fetcha['koordinat'];
		$exp_koordinat 	= explode(',', $koordinat);
		$json_koordinat	= '{"lat": '.$exp_koordinat[0].', "lng": '.$exp_koordinat[1].'}';
		
		echo "<option value='$json_koordinat' data-tujuan='$fetcha[tujuan]'>$fetcha[tujuan]</option>";
	}
echo '';
?>
									</select>
								</div><br>
							</div>
							<div class="form-group col-md-12">
								<button class="btn btn-success pull-right" onclick="send_dijkstra()" id='run_dijkstra'>RUN</button>
							</div>
						</form>
						<span id='loading' style='display:none'>membuat rute ..</span>
						<div id='DEBUG'></div>
						</div>	
					</div>
					<div class="panel panel-info">
						<div class="panel-heading">
								<h2><i class="fa fa-car"></i> Hasil Hitung Jarak Dan Waktu Tempuh</h2>
						</div>
						<div class="panel-body">
<?php
$last_id = $_GET['id'];
$result = mysqli_query($con, "SELECT * FROM requests where id='$last_id'");
$getdata = mysqli_fetch_array($result);

function curl_get_contents($url)
{
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url);
$data = curl_exec($ch);
curl_close($ch);
return $data;
}
$a="-6.9940775,110.4218061";
$t="-7.218713463461455,110.42975306510925";
$asal=urlencode($a);
$tujuan=urlencode($t);
$result= curl_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?origins=$asal&destinations=$tujuan&mode=bicycling&language=fr-FR&key=AIzaSyDfUutw5kI2URradTkzL1C409sqildXJNc");
$obj = json_decode($result, true);
?>
							<form action="" method="post">
								<input type="hidden" name="id" value="<?= $getdata['id'] ?>">
								<input type="hidden" name="created_at" value="<?= $getdata['created_at'] ?>">
								<dl>
									<dt>Lokasi Asal</dt>
									<dd><input readonly class="form-control" type="text" name="asal" id="asal" value="TELKOM SEMARANG"></dd>
									<dt>Lokasi Tujuan</dt>
									<dd><input readonly class="form-control" type="text" name="tujuan" id="tujuan"></dd>
									<dt>Jarak</dt>
									<dd><input readonly class="form-control" type="text" name="jarak" id="jarak"></dd>
									<dt>Waktu</dt>
									<dd><input readonly class="form-control" type="text" name="waktu" id="waktu"></dd>
									<dt>BBM</dt>
									<dd><input readonly class="form-control" type="text" name="bbm" id="bbm"></dd>
								</dl>
								<button type="submit" button onclick="sweetalertclick()" class="btn btn-primary pull-right" name="submit" value="submit">Simpan</button>
							</form>
						</div>
					</div>
				</div>
			</div>
	</div>
            
		<script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
	</body>
</html>