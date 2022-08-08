<?php

$dbhost = 0;
if (isset($_POST['dbhost'])){ $dbhost = $_POST['dbhost']; }
$dbport = 0;
if (isset($_POST['dbport'])){ $dbport = $_POST['dbport']; }
$dbdb = 0;
if (isset($_POST['dbdb'])){ $dbdb = $_POST['dbdb']; }
$dbuser = 0;
if (isset($_POST['dbuser'])){ $dbuser = $_POST['dbuser']; }
$dbpass = 0;
if (isset($_POST['dbpass'])){ $dbpass = $_POST['dbpass']; }
$anquadrimestre = 0;
if (isset($_POST['anquadrimestre'])){ $anquadrimestre = $_POST['anquadrimestre']; }
$anano = 0;
if (isset($_POST['anano'])){ $anano = $_POST['anano']; }
$andtinicial = 0;
if (isset($_POST['andtinicial'])){ $andtinicial = $_POST['andtinicial']; }
$andtfinal = 0;
if (isset($_POST['andtfinal'])){ $andtfinal = $_POST['andtfinal']; }
$anperiodo = 0;
if (isset($_POST['anperiodo'])){ $anperiodo = $_POST['anperiodo']; }
$tprel = 0;
if (isset($_POST['tprel'])){ $tprel = $_POST['tprel']; }
$motor = 0;
if (isset($_POST['motor'])){ $motor = $_POST['motor']; }
$i4eu = "1";
if (isset($_POST['i4eu'])){ $i4eu = $_POST['i4eu']; }
$vts = "0";
if (isset($_POST['vts'])){ $vts = $_POST['vts']; }
$par6 = "00000";
if (isset($_POST['par6'])){ $par6 = $_POST['par6']; }
$par7 = "0000";
if (isset($_POST['par7'])){ $par7 = $_POST['par7']; }
$par123 = "0000";
if (isset($_POST['par123'])){ $par123 = $_POST['par123']; }
$parv = "180";
if (isset($_POST['parv'])){ $parv = $_POST['parv']; }
$parvi = "33";
if (isset($_POST['parvi'])){ $parvi = $_POST['parvi']; }
$parvid = "0";
if (isset($_POST['parvid'])){ $parvid = $_POST['parvid']; }

$texto = "
<?php\r\n
\$dbhost = \"".$dbhost."\";\r\n
\$dbport = \"".$dbport."\";\r\n
\$dbdb = \"".$dbdb."\";\r\n
\$dbuser = \"".$dbuser."\";\r\n
\$dbpass = \"".$dbpass."\";\r\n
\$anquadrimestre = \"".$anquadrimestre."\";\r\n
\$anano = \"".$anano."\";\r\n
\$andtinicial = \"".$andtinicial."\";\r\n
\$andtfinal = \"".$andtfinal."\";\r\n
\$anperiodo = \"".$anperiodo."\";\r\n
\$tprel = \"".$tprel."\";\r\n
\$motor = \"".$motor."\";\r\n
\$i4eu = \"".$i4eu."\";\r\n
\$vts = \"".$vts."\";\r\n
\$par123 = \"".$par123."\";\r\n
\$par6 = \"".$par6."\";\r\n
\$par7 = \"".$par7."\";\r\n
\$parv = \"".$parv."\";\r\n
\$parvi = \"".$parvi."\";\r\n
\$parvid = \"".$parvid."\";\r\n
?>
";
$arq_pa = "config.php";
if (file_exists($arq_pa)) {
	unlink($arq_pa);
}
$arquivo_pa = fopen($arq_pa,'w');
fwrite($arquivo_pa, $texto);
fclose($arquivo_pa);

$arq_proc = "rel/".$tprel.".php";
$arq_rel = "html/".$tprel.".html";

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
        <title>Relatório</title>
        <link rel="stylesheet" href="jquery/css/bootstrap/bootstrap-3.3.7.min.css">
        <script src="jquery/js/jquery-2.2.4.min.js"></script>
        <script src="jquery/js/bootstrap-3.3.7.min.js"></script>
        <style>
            /*Regra para a animacao*/
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            /*Mudando o tamanho do icone de resposta*/
            div.glyphicon {
                color:#6B8E23;
                font-size: 38px;
            }
            /*Classe que mostra a animacao 'spin'*/
            .loader {
              border: 16px solid #f3f3f3;
              border-radius: 50%;
              border-top: 16px solid #3498db;
              width: 80px;
              height: 80px;
              -webkit-animation: spin 2s linear infinite;
              animation: spin 2s linear infinite;
            }
        </style>
        <script>
            //$(function () {
                //Comportamento do botao de disparo
                //$('#btn-getResponse').click(function () {
                    //getResponse();
                //});
            //});
            /**
             * Dispara o modal e espera a resposta do script 'testing.resp.php'
             * @returns {void}
             */
            function getResponse() {
                //Preenche e mostra o modal
                $('#loadingModal_content').html('Carregando...');
                $('#loadingModal').modal('show');
                //Envia a requisicao e espera a resposta
                $.post("<?php echo $arq_proc;?>")
                    .done(function () {
                        //Se nao houver falha na resposta, preenche o modal
                        $('#loader').removeClass('loader');
                        $('#loader').addClass('glyphicon glyphicon-ok');
                        $('#loadingModal_label').html('Sucesso!');
                        $('#loadingModal_content').html('<br>Relatorio gerado com sucesso!');
						resetModal();
						
						window.location.href = 'index.php?vts=<?php echo $vts; ?>&i4eu=<?php echo $i4eu; ?>';
						let a = document.createElement('a');
						a.target = '_blank';
						a.href = '<?php echo $arq_rel;?>';
						a.click();
						
						//$(window).load('<?php echo $arq_rel;?>');
						//$('#container').load('<?php echo $arq_rel;?>');
                        //resetModal();
                    })
                    .fail(function () {
                        //Se houver falha na resposta, mostra o alert
                        $('#loader').removeClass('loader');
                        $('#loader').addClass('glyphicon glyphicon-remove');
                        $('#loadingModal_label').html('Falha!');
                        $('#loadingModal_content').html('<br>Relatorio nao gerado!');
                        resetModal();
                    });
            }
            function resetModal(){
                //Aguarda 2 segundos ata restaurar e fechar o modal
                setTimeout(function() {
                    $('#loader').removeClass();
                    $('#loader').addClass('loader');
                    $('#loadingModal_label').html('<span class="glyphicon glyphicon-refresh"></span>Aguarde...');
                    $('#loadingModal').modal('hide');
                }, 2000);
            }
            $(function () {
                 getResponse();
            });
        </script>
    </head>
    <body>
        <!-- loadingModal-->
        <div class="modal fade" data-backdrop="static" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModal_label">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loadingModal_label">
                            <span class="glyphicon glyphicon-refresh"></span>
                            Aguarde...
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class='alert' role='alert'>
                            <center>
                                <div class="loader" id="loader"></div><br>
                                <h4><b id="loadingModal_content"></b></h4>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- loadingModal-->
        <nav class="navbar"></nav>
        <div class="container" id="container">
        </div>
    </body>
</html>
