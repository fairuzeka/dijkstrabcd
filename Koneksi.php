<?php
class Koneksi{
	public $db = "test";
	
	public function connect(){
		return mysqli_connect('localhost', 'root', '','test');
	}
	
	public function connectPDO(){
		return $conn = new PDO("mysql:host=localhost;dbname=$this->db", 'root', '');
	}
}
?>