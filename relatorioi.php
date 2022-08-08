<?php
include('config.php');
include('functions.php');
if ($anperiodo == "B"){
	$dataI = dataint($andtinicial,"b");
	$dataF = dataint($andtfinal,"b");
} else {
	if ($anquadrimestre == 1){
		$dataI = (int) $anano."0101";
		$dataF = (int) $anano."0430";
	}
	if ($anquadrimestre == 2){
		$dataI = (int) $anano."0501";
		$dataF = (int) $anano."0831";
	}
	if ($anquadrimestre == 3){
		$dataI = (int) $anano."0901";
		$dataF = (int) $anano."1231";
	}
}

include('sobre.php');
$telId = "";
if (isset($_GET['rlid'])){ $telId = $_GET['rlid']; }

$linhafinal = "";
if (strlen($telId) > 0){
	$arq_r = "csv/".$telId.".csv"; 
	if (file_exists($arq_r)) {
		$arquivo_rel = fopen($arq_r,'r');
		$num_linhas = count(file($arq_r));
		$n_linha = 1;
		while(!feof($arquivo_rel)) {
			$line = fgets($arquivo_rel);
			if (strlen($line) > 0){
				$val_arr = explode(";",$line);
				$novalinha = "[";
				for($i=0;$i<count($val_arr);$i++){
					$novalinha.="'".trim($val_arr[$i])."',";
				}
				$novalinha = substr($novalinha,0,strlen($novalinha)-1)."],";
				if ($n_linha == $num_linhas){
					$novalinha = substr($novalinha,0,strlen($novalinha)-1);
				}
				if ($n_linha > 1){
					$linhafinal .= $novalinha;
				}
			}
			$n_linha++;
		}
		fclose($arquivo_rel);
	}
}
include ("rel/".$telId."_C.php");

?>
<!DOCTYPE HTML>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="sortcut icon" href="images/favicon.ico" type="image/x-icon" />
  <title>Relatório individualizado</title>
    <script src="jquery/js/jquery-3.6.0.min.js"></script>    
    <script src="jquery/js/jquery-ui-1.9.2.min.js"></script>
	<script src="jquery/js/pqgrid-2.4.1.min.js"></script>
	<link rel="stylesheet" href="jquery/css/base/jquery-ui-1.9.2.min.css" />
    <link rel="stylesheet" href="jquery/css/pqgrid/base/pqgrid-2.4.1.min.css" />
    <link rel="stylesheet" href="jquery/css/pqgrid/office/pqgrid-2.4.1.css" />

<script>
    $(function () {
		
		//https://paramquery.com/demos
        //function pqDatePicker(ui) {
            //var $this = $(this);
           // $this
            //.css({ zIndex: 3, position: "relative" })
            //.datepicker({
                //yearRange: "-25:+0", //25 years prior to present.
                //changeYear: true,
                //changeMonth: true,
                //showButtonPanel: true,
                //onClose: function (evt, ui) {
                   // $(this).focus();
                //}
            //});
        //};
		
        var data = [
<?php
echo $linhafinal;
?>
			];

        var obj = { 
            width: "auto",
            height: 500,
            sortable:true,
            filterModel: { on: true, mode: "AND", header: true },
            //scrollModel: { autoFit: true },
            collapsible: { on: true, collapsed: false },
            wrap: false, 
			hwrap: false,
            //freezeCols: 4,
            title: "Relatório individualizado"
        };
        obj.colModel = [	
<?php
echo $cabecalho;
?>	
        ];
        obj.pageModel = {
            type:"local",
            rPP: 100,
            rPPOptions: [100, 200, 500, 10000]
        };
        obj.dataModel = { data: data };
		var $grid = $("#grid_array").pqGrid(obj);
		$grid.css('margin', "1px");
    });
</script>
<style>
h1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 20px;
}
.p1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
}
</style>
</head>
<body>
<?php
echo $cabeca;
?>
<div id="grid_array" style="margin:100px;"></div>
</body>

</html>
