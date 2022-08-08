<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Colaboradores</title>
  <script src="jquery/js/js/modernizr.js" type="text/javascript"></script>
  <link rel="stylesheet" href="jquery/css/normalize/normalize-5.0.0.min.css">
  <link rel="stylesheet" href="jquery/css/normalize/styletb.css">
</head>
<body>
  <h1>Colaboradores</h1>
<table class="rwd-table">
  <tr>
    <th>Nome</th>
    <th>E-mail</th>
    <th>Telegram/Whats</th>
  </tr>
<?php
include('sobre.php');
$show = "";
for ($i=0;$i<count($colaboradores);$i++){
	$show .= "
	  <tr>
		<td>".$colaboradores[$i]['nome']."</td>
		<td>".$colaboradores[$i]['email']."</td>
		<td>".$colaboradores[$i]['tw']."</td>
	  </tr>
	";
}
echo $show;
?>
</table>
  <script src='jquery/js/jquery-3.6.0.min.js'></script>
  <script src="jquery/js/js/indextb.js"></script>
</body>
</html>