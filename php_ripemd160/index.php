<html><body>


<?php
/*
* пример получения адреса с использование внутреннего алгорима ripemd160
*   входящий парамет массив публичного ключа
* 
*/

	include 'ripemd160_era.php';
	include 'base58.php';
	
	
	
	// проверка  createaddress.html  из ERA-Tutorial
	// поле "Enter string (min 8 Characters):" = 22222222
	// задаем публичный ключ явно
	// публичный ключ для первого адреса
	$publicKey = array(144, 238, 201, 64, 211, 124, 199, 112, 250, 35, 134, 11, 74, 240, 78, 168, 188, 45, 31, 18, 74, 220, 230, 134, 11, 7, 161, 255, 206, 167, 149, 64);
	// публичный ключ для второго адреса
	//$publicKey= array(21, 125, 70, 225, 1, 203, 81, 214, 190, 153, 238, 208, 47, 74, 89, 62, 248, 226, 50, 39, 52, 62, 180, 15, 213, 112, 215, 46, 159, 39, 49, 160);
	
	// вычисление адреса
	// версия адреса
	$ADDRESS_VERSION = 15; 
	// sha256 
	// convert array -> string
	$string = implode(array_map("chr", $publicKey));
	//string to String sha256
	$haspublicKey = hash("sha256", $string, true);
	// convert String sha256 -> array
	$publickey1 = unpack('C*', $haspublicKey);
	// сдвиг на одину позицию 
	$publickey2 = array_slice($publickey1, 0, 32);
 	// calc ripemd160
	$ee = ripemd160_digest($publickey2);
	// add version
	array_unshift ( $ee, $ADDRESS_VERSION );
	
	// check summ
	// sha256 1 step
	// convert array -> string
	$string = implode(array_map("chr", $ee));
	$hashee = hash("sha256", $string, true);
	// sha256 2 step
	$hashee = hash("sha256", $hashee, true);
	// get 4 first byte	
	$first4byte = substr($hashee,0,4); 
	// add 4 byte to end
	$e1 = $string. $first4byte;
	// convert string -> hex 
	$hex = implode(unpack("H*", $e1));
	// hex -> base 58
	$base58 = encodeBase58($hex);
		  
	echo "<br>base58  address:  ";
	print_r($base58);
	
?>

</body></html>