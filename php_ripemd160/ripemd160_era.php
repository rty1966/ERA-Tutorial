<?php

$ArgArray =array (
array(11, 14, 15, 12, 5, 8, 7, 9, 11, 13, 14, 15, 6, 7, 9, 8, 7, 6, 8, 13, 11, 9, 7, 15, 7, 12, 15, 9, 11, 7, 13, 12, 11, 13, 6, 7, 14, 9, 13, 15, 14, 8, 13, 6, 5, 12, 7, 5, 11, 12, 14, 15, 14, 15, 9, 8, 9, 14, 5, 6, 8, 6, 5, 12, 9, 15, 5, 11, 6, 8, 13, 12, 5, 12, 13, 14, 11, 8, 5, 6),
array(8, 9, 9, 11, 13, 15, 15, 5, 7, 7, 8, 11, 14, 14, 12, 6, 9, 13, 15, 7, 12, 8, 9, 11, 7, 7, 12, 7, 6, 15, 13, 11, 9, 7, 15, 11, 8, 6, 6, 14, 12, 13, 5, 14, 13, 13, 7, 5, 15, 5, 8, 11, 14, 14, 6, 14, 6, 9, 12, 9, 12, 5, 15, 8, 8, 5, 12, 9, 12, 5, 14, 6, 8, 13, 6, 5, 15, 13, 11, 11)
);
  $IndexArray = array(
  array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 7, 4, 13, 1, 10, 6, 15, 3, 12, 0, 9, 5, 2, 14, 11, 8, 3, 10, 14, 4, 9, 15, 8, 1, 2, 7, 0, 6, 13, 11, 5, 12, 1, 9, 11, 10, 0, 8, 12, 4, 13, 3, 7, 15, 14, 5, 6, 2, 4, 0, 5, 9, 7, 12, 2, 10, 14, 1, 3, 8, 11, 6, 15, 13), 
  array(5, 14, 7, 0, 9, 2, 11, 4, 13, 6, 15, 8, 1, 10, 3, 12, 6, 11, 3, 7, 0, 13, 5, 10, 14, 15, 8, 12, 4, 9, 1, 2, 15, 5, 1, 3, 7, 14, 6, 9, 11, 8, 12, 2, 10, 0, 4, 13, 8, 6, 4, 1, 3, 11, 15, 0, 5, 12, 2, 13, 9, 7, 10, 14, 12, 15, 10, 4, 1, 5, 8, 7, 6, 2, 13, 14, 0, 3, 9, 11)
  );
 
  	
    function reset160  () {
	 global  $InputArray,  $working_ptr, $working,$MDbuf, $msglen;
	$MDbuf = array((int)(1732584193), (int)(4023233417), (int)(2562383102), (int)(271733878), (int) (3285377520));
    $working = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
	$working_ptr = 0;
	 $msglen = 0;
     }
 
 function update_160 ($input) {
 global  $working_ptr, $working, $msglen;
	        for ($i = 0; $i <  count($input); $i++) {
		if ($input[$i] >=128) $input[$i]=  $input[$i]-256;
			$working[$working_ptr >> 2] ^= (($input[$i]) << (($working_ptr & 3) << 3));
			$working_ptr++;
            if (($working_ptr == 64)) {
                compress($working);
                for ($j = 0; $j < 16; $j++) {
                   $working[$j] = 0;
                }
                $working_ptr = 0;
            }
        }
		$msglen += count($input);
    };
 
  function ripemd160_digest  ($input) {
	reset160();
    update_160( $input);
     return digestBin();
  };
 
 function compress ($X) {
 global $MDbuf, $IndexArray, $ArgArray;
        $index = 0;
        $temp=0;
        $s=0;
        $A = $a = $MDbuf[0];
        $B = $b = $MDbuf[1];
        $C = $c = $MDbuf[2];
        $D = $d = $MDbuf[3];
        $E = $e = $MDbuf[4];

		
        for (; $index < 16; $index++) {
	//		echo "<br><br> цикл1 index=$index <br> X=";
	//		print_r($X);
            $temp =get4byte (get4byte($a + ($b ^ $c ^ $d)) + $X[$IndexArray[0][$index]]);
            $a = get4byte($e);
            $e = get4byte($d);
            $d = get4byte( ($c << 10) | shift($c ,22));
	        $c = get4byte($b);
            $s = get4byte($ArgArray[0][$index]);
			$b = get4byte(get4byte(($temp <<$s) | shift($temp, (32 - $s))) + $a);
			$temp =get4byte( get4byte($A + ($B ^ ($C | ~$D))) + $X[$IndexArray[1][$index]] + 1352829926);
			$A = get4byte($E);
            $E = get4byte($D);
            $D = get4byte(($C <<10) | shift($C , 22));
	        $C = get4byte($B);
            $s = get4byte($ArgArray[1][$index]);
 			$B = get4byte(get4byte(($temp << $s) | shift($temp , (32 - $s)))+ $A);
	//		 echo ("<br> a=$a, A=$A, b=$b, B=$B, c=$c, C=$C,  d=$d, D=$D, e=$e, E=$E, temp=$temp s=$s , index = $index");
        }

        for (; $index < 32; $index++) {
	//		echo "<br><br> цикл2 index=$index";
            $temp = get4byte(get4byte($a + (($b & $c) | (~$b & $d))) + $X[$IndexArray[0][$index]] + 1518500249);
            $a = get4byte($e);
            $e = get4byte($d);
 			$d = get4byte(($c << 10) | shift($c , 22));
            $c = get4byte($b);
            $s = get4byte((int)($ArgArray[0][$index]));
			$b = get4byte(get4byte(($temp << $s) | shift($temp , (32 - $s))) + $a);
            $temp = get4byte(get4byte($A + (($B &$D) | ($C & ~$D))) + $X[$IndexArray[1][$index]] + 1548603684);
            $A = get4byte($E);
            $E = get4byte($D);
            $D =get4byte( ($C << 10) | shift($C , 22));
            $C = get4byte($B);
            $s =get4byte($ArgArray[1][$index]);
			 $B =get4byte( get4byte(($temp << $s) | shift($temp , (32 - $s))) + $A);
	//		 echo ("<br> a=$a, A=$A, b=$b, B=$B, c=$c, C=$C,  d=$d, D=$D, e=$e, E=$E, temp=$temp s=$s , index = $index");
        }
        for (; $index < 48; $index++) {
	//		echo "<br><br> цикл3 index=$index";
            $temp = get4byte(get4byte($a + get4byte(($b | ~$c) ^ $d)) + $X[$IndexArray[0][$index]] + 1859775393);
            $a = get4byte($e);
            $e =get4byte($d);
	        $d = get4byte(($c << 10) | shift($c , 22));
            $c =get4byte($b);
            $s = get4byte($ArgArray[0][$index]);
 			$b = get4byte(get4byte(($temp << $s) | shift($temp , (32 - $s))) + $a);
            $temp = get4byte(get4byte($A + (($B | ~$C) ^ $D)) + $X[$IndexArray[1][$index]] + 1836072691);
            $A = get4byte($E);
            $E = get4byte($D);
			$D = get4byte($C << 10) | shift($C , 22);
            $C = get4byte($B);
            $s = get4byte($ArgArray[1][$index]);
 			$B = get4byte(get4byte(($temp << $s) | shift($temp , (32 - $s))) + $A);
	//		echo ("<br> a=$a, A=$A, b=$b, B=$B, c=$c, C=$C,  d=$d, D=$D, e=$e, E=$E, temp=$temp s=$s , index = $index");
        }
       for (; $index < 64; $index++) {
	//		echo "<br><br> цикл4 index=$index";
            $temp = get4byte(get4byte($a + get4byte(($b & $d) | ($c & ~$d))) + $X[$IndexArray[0][$index]] + 2400959708);
            $a = get4byte($e);
            $e = get4byte($d);
			$d = get4byte(($c << 10) | shift($c , 22));
            $c = get4byte($b);
            $s =get4byte($ArgArray[0][$index]);
			$b = get4byte(get4byte(($temp << $s) | shift($temp , (32 - $s))) + $a);
            $temp = get4byte(get4byte($A + (($B & $C) | (~$B & $D))) + $X[$IndexArray[1][$index]] + 2053994217);
            $A = get4byte($E);
            $E = get4byte($D);
			$D = get4byte(($C << 10) | shift($C , 22));
            $C = get4byte($B);
            $s = get4byte($ArgArray[1][$index]);
			$B = get4byte(get4byte(($temp << $s) | shift($temp , (32 - $s))) + $A);
	//		echo ("<br> a=$a, A=$A, b=$b, B=$B, c=$c, C=$C,  d=$d, D=$D, e=$e, E=$E, temp=$temp s=$s , index = $index");
        }
        for (; $index < 80; $index++) {
	//		echo "<br><br> цикл5 index=$index";
            $temp = get4byte(get4byte($a + get4byte($b ^ ($c | ~$d)) )+ $X[$IndexArray[0][$index]] + 2840853838);
            $a = get4byte($e);
            $e = get4byte($d);
            $d = get4byte(($c << 10) | shift($c , 22));
            $c = get4byte($b);
            $s = get4byte($ArgArray[0][$index]);
			$b = get4byte(get4byte(($temp << $s) | shift($temp , (32 - $s))) + $a);
            $temp = get4byte(get4byte($A + ($B ^ $C ^ $D)) + $X[$IndexArray[1][$index]]);
            $A = get4byte($E);
            $E = get4byte($D);
            $D = get4byte(($C << 10) | shift($C , 22));
            $C = get4byte($B);
            $s = get4byte($ArgArray[1][$index]);
 			 $B = get4byte(get4byte(($temp << $s) | shift($temp , (32 - $s))) + $A);
	//	echo ( " <br> a=$a, A=$A, b=$b, B=$B, c=$c, C=$C,  d=$d, D=$D, e=$e, E=$E, temp=$temp s=$s , index = $index");
        }
        $D = (int)($D +  $c + $MDbuf[1]);
        $MDbuf[1] = (int)($MDbuf[2] + $d +$E);
        $MDbuf[2] = (int)($MDbuf[3] + $e + $A);
        $MDbuf[3] = (int)($MDbuf[4] + $a + $B);
        $MDbuf[4] = (int)($MDbuf[0] + $b + $C);
        $MDbuf[0] = (int)$D;
    };
 
  function digestBin () {
  global $MDbuf, $working, $msglen;
        MDfinish($working, $msglen, 0);
 		$res = array();
		for ($i = 0; $i < 20; $i++) {
			 $res[$i] = (shift($MDbuf[$i >> 2] ,(($i & 3) << 3)) & 255);
        }
       return $res;
    };
 function MDfinish ($array, $lswlen, $mswlen) {
        $X = $array;
        $X[($lswlen >> 2) & 15] ^= 1 << ((($lswlen & 3) << 3) + 7);
        if ((($lswlen & 63) > 55)) {
            compress($X);
            for ($i = 0; $i < 14; $i++) {
                $X[$i] = 0;
            }
        }
        $X[14] = $lswlen << 3;
        $X[15] = ($lswlen >> 29) | ($mswlen << 3);
        compress($X);
    };
	
	// сдвиг >>>
	function shift($a,$n){
		if($n==0) return $a;
		$k=decbin($a);
		// оставляем первые 8 байт
		$rest = substr($k, -32, 32);
		$l =strlen($rest);
		// сдвигаем на н символов
		return bindec(substr($rest, 0, -$n));
	}
	
	function get4byte($val){
	return $val & 4294967295;
	}
	
	function get1byte($val){
	return $val&255;	
	}
?>