<?php
   require_once('dim/DimCliente.php');
   
   require_once('dim/Sumario.php');

   require_once('dim/DimProduto.php');

   use dimensoes\Sumario;
   
   use dimensoes\DimCliente;

   use dimensoes\DimProduto;

   $dimCliente = new DimCliente();
   $sumCliente = $dimCliente->carregarDimCliente();
   echo "Clientes: <br>";
   echo "Inclusões: ".$sumCliente->quantidadeInclusoes."<br>";
   echo "Alterações: ".$sumCliente->quantidadeAlteracoes."<br>";
   echo "<br>==============================================<br>";

   $dimProduto = new DimProduto();
   $sumProduto = $dimProduto->carregarDimProduto();

   echo "Produtos <br>";
   echo "Inclusões: ".$sumProduto->quantidadeInclusoes."<br>";
   echo "Alterações: ".$sumProduto->quantidadeAlteracoes."<br>";
   echo "<br>==============================================<br>";

?>