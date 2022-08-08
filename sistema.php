<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Sistema</title>
  <script src="jquery/js/js/modernizr.js" type="text/javascript"></script>
  <link rel="stylesheet" href="jquery/css/normalize/normalize-5.0.0.min.css">
  <link rel="stylesheet" href="jquery/css/normalize/styletb.css">
</head>
<body>
  <h1>Dados do script</h1>
<table class="rwd-table">
<?php
include('sobre.php');
?>
  <tr>
    <td>Nome</td>
    <td><?php echo $sobre['nome']; ?></td>
  </tr>
  <tr>
    <td>Versão</td>
    <td><?php echo $sobre['versao']; ?></td>
  </tr>
  <tr>
    <td>Última alteração</td>
    <td><?php echo $sobre['alteracao']; ?></td>
  </tr>
  <tr>
    <td>Repositório 1</td>
    <td><?php echo $sobre['repositorio1']; ?></td>
  </tr>
  <tr>
    <td>Ferramentas</td>
    <td><?php echo $sobre['ferramentas']; ?></td>
  </tr>
  <tr>
    <td>Canal Youtube</td>
    <td><?php echo $sobre['canal']; ?></td>
  </tr>
</table>
  <script src='jquery/js/jquery-3.6.0.min.js'></script>
  <script src="jquery/js/js/indextb.js"></script>
</body>
</html>