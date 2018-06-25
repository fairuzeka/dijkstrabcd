<?php

$asal   = !empty($_POST['asal']) ? urlencode($_POST['asal']) : null;

$tujuan = !empty($_POST['tujuan']) ? urlencode($_POST['tujuan']) : null;

$urlApi = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$asal."&destinations=".$tujuan."&language=id-ID";

$result = file_get_contents($urlApi);

$data   = json_decode($result, true);

?>

<p>
    Alamat Asal : <br />
    <strong><?php echo $data['origin_addresses'][0] ?></strong>
</p>

<p>
    Alamat Tujuan : <br />
    <strong><?php echo $data['destination_addresses'][0] ?></strong>
</p>

<p>
    Jarak Tempuh : <br />
    <strong><?php echo $data['rows'][0]['elements'][0]['distance']['text'] ?></strong>
</p>

<p>
    Waktu Tempuh : <br />
    <strong><?php echo $data['rows'][0]['elements'][0]['duration']['text'] ?></strong>
</p>

<button onclick="history.back()">Kembali</button>