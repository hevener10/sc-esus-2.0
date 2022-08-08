<?php
if (isset($_GET['f'])){ $f = $_GET['f']; }

$arq_r = "ajuda/".$f.".txt"; 
if (file_exists($arq_r)) {
	$arquivo_rel = fopen($arq_r,'r');
	while(!feof($arquivo_rel)) {
		$line = fgets($arquivo_rel);
		echo $line."<br>";
	}
}

?>