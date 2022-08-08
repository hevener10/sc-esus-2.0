<?php
date_default_timezone_set('America/Sao_Paulo');
$vts = "0";
if (isset($_POST['vts'])){ $vts = $_POST['vts']; }

include('rels.php');
$relsH = array();
$dirHTML = "html/";
$countarrayH = 0;
$cdir = scandir($dirHTML);
foreach ($cdir as $key => $value){
	if (!in_array($value,array(".",".."))){
		if (is_file($dirHTML.$value)){
			$size = filesize($dirHTML.$value);
			$datef = filemtime($dirHTML.$value);
			if ($size > 0){
				$parArq = explode(".",$value);
				if (strtolower($parArq[1]) == "html"){
					$relsH[$countarrayH][0] = $parArq[0];
					$relsH[$countarrayH][1] = $parArq[1];
					$relsH[$countarrayH][2] = round($size/1024,1);
					$relsH[$countarrayH][3] = date("d/m/Y H:i:s",$datef);
					$countarrayH++;
				}
			}
		}
	}
}
$relsC = array();
$dirCSV = "csv/";
$countarrayC = 0;
$cdir = scandir($dirCSV);
foreach ($cdir as $key => $value){
	if (!in_array($value,array(".",".."))){
		if (is_file($dirCSV.$value)){
			$size = filesize($dirCSV.$value);
			$datef = filemtime($dirCSV.$value);
			if ($size > 0){
				$parArq = explode(".",$value);
				if (strtolower($parArq[1]) == "csv"){
					$relsC[$countarrayC][0] = $parArq[0];
					$relsC[$countarrayC][1] = $parArq[1];
					$relsC[$countarrayC][2] = round($size/1024,1);
					$relsC[$countarrayC][3] = date("d/m/Y H:i:s",$datef);
					$countarrayC++;
				}
			}
		}
	}
}
$nshow = "";
for($x=0;$x<count($relatorios);$x++){
	for($i=0;$i<count($relsH);$i++){
		if ($relatorios[$x][0] == $relsH[$i][0]){
			$nshow .= "<tr><td>".$relatorios[$x][3]."</td>";
			if ($relsH[$i][1] == "html") {
				$nshow .= "<td><a href=\"html/".$relsH[$i][0].".html\" target='_blank'><img src=\"images/html.png\"></a></td>";
				$nshow .= "<td>".$relsH[$i][3]." [".$relsH[$i][2]." Kb]</td>";
			}
			$nshow .= "</tr>";
			break;
		}
	}
}
$nshowscv = "";
for($x=0;$x<count($relatorios);$x++){
	for($i=0;$i<count($relsC);$i++){
		if ($relatorios[$x][0] == substr($relsC[$i][0],0,4)){
			$nshowscv .= "<tr><td>".$relatorios[$x][3]." - ".substr($relsC[$i][0],5)."</td>";
			if ($relsC[$i][1] == "csv") {
				$nshowscv .= "<td><a href=\"csv/".$relsC[$i][0].".csv\" target='_blank'><img src=\"images/csv.png\"></a></td>";
				$nshowscv .= "<td>".$relsC[$i][3]." [".$relsC[$i][2]." Kb]</td>";
			}
			$nshowscv .= "</tr>";
		}
	}
}

?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Relatórios já criados</title>
  <script src="jquery/js/js/modernizr.js" type="text/javascript"></script>
  <link rel="stylesheet" href="jquery/css/normalize/normalize-5.0.0.min.css">
  <link rel="stylesheet" href="jquery/css/normalize/styletb.css">
</head>
<body>
  <h1>Relatórios criados anteriormente</h1>
<table class="rwd-table">
  <tr>
    <th>Relatório</th>
    <th>HTML</th>
    <th>Arquivo</th>
  </tr>
<?php echo $nshow; ?>
</table>
<?php
/*
echo "
<table class=\"rwd-table\">
  <tr>
    <th>Relatório</th>
    <th>CSV</th>
    <th>Arquivo</th>
  </tr>
".$nshowscv."
</table>";
*/
?>

  <script src='jquery/js/jquery-3.6.0.min.js'></script>
  <script src="jquery/js/js/indextb.js"></script>
</body>
</html>