<?php
class Koneksi{
	public $db = "googlemaps_algo_dijkstra";
	
	public function connect(){
		return mysqli_connect('localhost', 'root', '', $this->db);
	}
	
	public function connectPDO(){
		return $conn = new PDO("mysql:host=localhost;dbname=$this->db", 'root', '');
	}
}
?>