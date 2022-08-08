<?php
include('sobre.php');
include('rels.php');
include('param.php');
require_once('functions.php');
$dbhost = "localhost";
$dbport = "5433";
$dbdb = "esus";
$dbuser = "postgres";
$dbpass = "esus";
$anquadrimestre = "1";
$anano = date("Y");
$andtinicial = "01".date("/m/Y");
$andtfinal = date("d/m/Y");
$anperiodo = "A";
$tprel = "0100";
$motor = "M1";
$i4eu = "1";
$vts = "0";
$par123 = "0000";
$par6 = "00000";
$par7 = "0000";
$parvi = "33";
$parv = "180";
$parvid = "0";
$arq_pa = "config.php";
if (file_exists($arq_pa)) {
	include($arq_pa);
}
if (isset($_GET['i4eu'])){ $i4eu = $_GET['i4eu']; }
if (isset($_GET['vts'])){ $vts = $_GET['vts']; }
?>
<!doctype html>

<html lang="en">

<head>
	<link rel="sortcut icon" href="images/favicon.ico" type="image/x-icon" />
    <title><?php echo $sobre['nome']; ?></title>

    <link rel="stylesheet" href="jquery/css/bootstrap/bootstrap-3.3.7.min.css">
    <style>
        .wrap { max-width: 980px; margin: 10px auto 0; }
        #steps { margin: 80px 0 0 0 }
        .commands { overflow: hidden; margin-top: 30px; }
        .prev {float:left}
        .next, .submit {float:right}
        .error { color: #b33; }
        #progress { position: relative; height: 5px; background-color: #eee; margin-bottom: 20px; }
        #progress-complete { border: 0; position: absolute; height: 5px; min-width: 10px; background-color: #337ab7; transition: width .2s ease-in-out; }
    </style>
  
    <script src="jquery/js/jquery-3.6.0.min.js"></script>
    <script src="jquery/js/jquery.validate-1.15.0.min.js"></script>
    <script src="jquery/js/jquery.formtowizard.js"></script>
    
    <script>
        $( function() {
            var $signupForm = $( '#SignupForm' );
            
            $signupForm.validate({
                errorElement: 'em',
                submitHandler: function (form) { 
                    //alert('O relatório escolhido pode demorar muitos minutos, tenha paciência!');
                    form.submit();
                }
            });
            
            $signupForm.formToWizard({
                submitButton: 'SaveAccount',
                nextBtnClass: 'btn btn-primary next',
                prevBtnClass: 'btn btn-default prev',
                buttonTag:    'button',
                validateBeforeNext: function(form, step) {
                    var stepIsValid = true;
                    var validator = form.validate();
                    $(':input', step).each( function(index) {
                        var xy = validator.element(this);
                        stepIsValid = stepIsValid && (typeof xy == 'undefined' || xy);
                    });
                    return stepIsValid;
                },
                progress: function (i, count) {
                    $('#progress-complete').width(''+(i/count*100)+'%');
                }
            });
        });
		function resizeIframe(obj) {
			obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
		}
    </script>
	<style>
	.p1 {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 30px;
		margin-top: 10px;
		margin-bottom: 10px;
		margin-left: 10px;
	}
	</style>
</head>

<body>
<p class="p1"><img src="images/logo2.png" style="width:100px;height:100px;">  <?php echo $sobre['nome']; ?></p>
<div class="row wrap"><div class="col-lg-12">
    <div id='progress'><div id='progress-complete'></div></div>
    <form id="SignupForm" action="prepara.php" method="post">
		<input type="hidden" id="i4eu" name="i4eu" value="<?php echo $i4eu;?>">
		<input type="hidden" id="vts" name="vts" value="<?php echo $vts;?>">
		<input type="hidden" id="motor" name="motor" value="<?php echo $motor;?>">
        <fieldset>
            <legend>Conexão com o banco de dados</legend>
            <div class="form-group">
            <label for="dbhost">Host</label>
            <input id="dbhost" name="dbhost" type="text" class="form-control" value="<?php echo $dbhost; ?>" required />
            </div>
            <div class="form-group">
            <label for="dbport">Porta</label>
            <input id="dbport" name="dbport" type="text" class="form-control" value="<?php echo $dbport; ?>" required />
            </div>
            <div class="form-group">
            <label for="dbdb">Banco</label>
            <input id="dbdb" name="dbdb" type="text" class="form-control" value="<?php echo $dbdb; ?>" required />
            </div>
            <div class="form-group">
            <label for="dbuser">Usuário</label>
            <input id="dbuser" name="dbuser" type="text" class="form-control" value="<?php echo $dbuser; ?>" required />
            </div>
            <div class="form-group">
            <label for="dbpass">Senha</label>
            <input id="dbpass" name="dbpass" type="text" class="form-control" value="<?php echo $dbpass; ?>" required />
            </div>
        </fieldset>
        <fieldset class="form-horizontal" role="form">
            <legend>Período de análise</legend>
            <div class="form-group">
            <label for="anquadrimestre" class="col-sm-2 control-label">Período A</label>
            <div class="col-sm-10"><div class="row">
                <div class="col-xs-3">
                <select id="anquadrimestre" name="anquadrimestre" class="form-control col-sm-2">
				<?php
				for ($i=1;$i<=3;$i++){
					$selec = "";
					if ($anquadrimestre == $i){$selec = "selected";}
					echo "<option value=\"".$i."\" ".$selec.">".$i."º Quadrimestre</option>";
				}
				?>
                </select>
                </div>
                <div class="col-xs-3">
                <select id="anano" name="anano" class="form-control">
				<?php
				$anoatual = (int) date("Y");
				$anofinal = $anoatual - 10;
				for ($i=$anoatual;$i>=$anofinal;$i--){
					$selec = "";
					if ($anano == $i){$selec = "selected";}
					echo "<option value=\"".$i."\" ".$selec.">".$i."</option>";
				}
				?>
                </select>        
                </div>
            </div></div>
            </div>
			
			
            <div class="form-group">
            <label for="andtinicial" class="col-sm-2 control-label">Período B</label>
            <div class="col-sm-10"><div class="row">
                <div class="col-xs-3">
                <input id="andtinicial" name="andtinicial" type="text" value="<?php echo $andtinicial; ?>" class="form-control" required />
                </div>
                <div class="col-xs-3">
                <input id="andtfinal" name="andtfinal" type="text" value="<?php echo $andtfinal; ?>" class="form-control" required />       
                </div>
				</div>
			</div>
            </div>
			
			
            <div class="form-group">
            <label for="anperiodo">Escolha o período para análise</label>
                <select id="anperiodo" name="anperiodo" class="form-control">
				<?php
					if ($anperiodo == "A"){
						  echo "<option value=\"A\" selected>Período A</option>";
						  echo "<option value=\"B\">Período B</option>";
					} else {
						  echo "<option value=\"A\">Período A</option>";
						  echo "<option value=\"B\" selected>Período B</option>";	
					}
				?>
                </select>
            </div>
        </fieldset>
        <fieldset class="form-horizontal" role="form">
            <legend>Relatório</legend>
            <div class="form-group">
            <label for="tprel">Escolha o relatório desejado</label>
                <select id="tprel" name="tprel" class="form-control">
				<?php
					$grupo = "";
					for($i=0;$i<count($relatorios);$i++){
						$selecionado = "";
						if ($tprel == $relatorios[$i][0]){
							$selecionado = "selected";
						}
						if (strlen($relatorios[$i][2]) > 0 && $grupo <> $relatorios[$i][2]){
							if ($relatorios[$i][1] == $vts)
								echo "<optgroup label=\"".$relatorios[$i][2]."\">";
							$grupo = $relatorios[$i][2];
						}
						if ($grupo <> $relatorios[$i][2]){
							if ($relatorios[$i][1] == $vts)
								echo "</optgroup>";
						}
						if ($relatorios[$i][1] == $vts)
							echo "<option value=\"".$relatorios[$i][0]."\" ".$selecionado.">".$relatorios[$i][3]."</option>";
					}
				?>
                </select>
            </div>
            <div class="form-group">
            <label for="par123">[Indicador 1, 2, 3 e BA-G] Parâmetros <a href="ajuda.php?f=pi123" target="_blank"><img src="images/help2.png"></a></label>
                <select id="par123" name="par123" class="form-control">
				<?php
					for($i=0;$i<count($param123);$i++){
						if ($param123[$i][1] == $par123){
							echo "<option value=\"".$param123[$i][1]."\" selected>".$param123[$i][0]."</option>";
						} else {
							echo "<option value=\"".$param123[$i][1]."\">".$param123[$i][0]."</option>";
						}
					}
				?>
                </select> 
            </div>
			
            <div class="form-group">
            <label for="par6">[Indicador 6 e BA-H] Parâmetros <a href="ajuda.php?f=pi6" target="_blank"><img src="images/help2.png"></a></label>
                <select id="par6" name="par6" class="form-control">
				<?php
					for($i=0;$i<count($param6);$i++){
						if ($param6[$i][1] == $par6){
							echo "<option value=\"".$param6[$i][1]."\" selected>".$param6[$i][0]."</option>";
						} else {
							echo "<option value=\"".$param6[$i][1]."\">".$param6[$i][0]."</option>";
						}
					}
				?>
                </select> 
            </div>
			
            <div class="form-group">
            <label for="par7">[Indicador 7 e BA-D] Parâmetros <a href="ajuda.php?f=pi7" target="_blank"><img src="images/help2.png"></a></label>
                <select id="par7" name="par7" class="form-control">
				<?php
					for($i=0;$i<count($param7);$i++){
						if ($param7[$i][1] == $par7){
							echo "<option value=\"".$param7[$i][1]."\" selected>".$param7[$i][0]."</option>";
						} else {
							echo "<option value=\"".$param7[$i][1]."\">".$param7[$i][0]."</option>";
						}
					}
				?>
                </select> 
            </div>
			
            <div class="form-group">
            <label class="col-sm-2 control-label">[BA-V e BA-COV]</label>
            <div class="col-sm-10"><div class="row">
                <div class="col-xs-3">Código do imunobiológico
                <input id="parvi" name="parvi" type="text" value="<?php echo $parvi; ?>" class="form-control" required />
                </div>
                <div class="col-xs-3">Idade
                <input id="parvid" name="parvid" type="text" value="<?php echo $parvid; ?>" class="form-control" required />
                </div>
                <div class="col-xs-3">Intervalo de tempo
                <select id="parv" name="parv" class="form-control">
				<?php
					for($i=0;$i<count($paramV);$i++){
						if ($paramV[$i][1] == $parv){
							echo "<option value=\"".$paramV[$i][1]."\" selected>".$paramV[$i][0]."</option>";
						} else {
							echo "<option value=\"".$paramV[$i][1]."\">".$paramV[$i][0]."</option>";
						}
					}
				?>
                </select>
                </div>
				</div>
			</div>
            </div>
			
			
        </fieldset>
        <button id="SaveAccount" type="submit" class="btn btn-primary submit">Próximo</button>
    </form>

</div></div>
<br>
<a href="doe.php" target="_blank"><img src="images/doe.png"></a>
<iframe src="relatorios.php?vts=<?php echo $vts; ?>" width="100%" style="border:none;" title="Relatorios" id="relatorios" frameborder="0" scrolling="no" onload="resizeIframe(this)" ></iframe>
<center><br>
Licença Apache 2.0<br>
<a href="https://www.apache.org/licenses/LICENSE-2.0" target="_blank"><img src="images/apache.png" style="width:192px;height:94px;"></a>
</center>
Olá, antes de tudo é importante que você compreenda o propósito do projeto. A ideia principal do projeto é trazer subsídios ao gestor de saúde para a tomada de decisões relacionadas ao programa Previne Brasil. O projeto tenta trazer dados sobre o andamento dos 7 indicadores (entre outros relatórios) permitindo que o gestor possua as informações necessárias para melhorar seus indicadores. É necessário registrar que o projeto dificilmente alcançará a precisão dos resultados informados no SISAB, visto que trabalha apenas com os denominadores informados e valida exclusivamente o município avaliado; Sua fonte de informação consiste nos dados contidos no PEC e-SUS APS. O projeto é livre e de código aberto (freeware and opensource) e se mantém com a ajuda de todos os interessados em melhorar a ferramenta que vem mantendo ritmo e aperfeiçoamento constante.<br><br>
</body>
</html>
