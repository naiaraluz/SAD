<?php
namespace dimensoes;
mysqli_report(MYSQLI_REPORT_STRICT);
require_once('Produto.php');
require_once('Sumario.php');
use dimensoes\Sumario;
use dimensoes\Produto;
class DimProduto{
   public function carregarDimProduto(){
      $sumario = new Sumario();
      try{
         $connDimensao = $this->conectarBanco('dm_comercial');
         $connComercial = $this->conectarBanco('bd_comercial');
      }catch(\Exception $e){
         die($e->getMessage());
      }
      $sqlDim = $connDimensao->prepare('select SK_produto, cod_produto, nome, uni_medida, preco
                                        from dm_produto');
      $sqlDim->execute();
      $result = $sqlDim->get_result();
      if($result->num_rows === 0){//Dimensão está
         $sqlComercial = $connComercial->prepare("select * from produto"); //Cria variável com comando SQL
         $sqlComercial->execute(); //Executa o comando SQL
         $resultComercial = $sqlComercial->get_result(); //Atribui à variával o resultado da consulta
         if($resultComercial->num_rows !== 0){ //Testa se a consulta retornou dados
            while($linhaProduto = $resultComercial->fetch_assoc()){ //Atibui à variável cada linha até o último
               $produto = new Produto();
               $produto->setProduto($linhaProduto['cod_produto'], $linhaProduto['nome'], $linhaProduto['uni_medida'], $linhaProduto['preco']);
               $slqInsertDim = $connDimensao->prepare("insert into dm_produto
                                                      (cod_produto, nome, uni_medida, preco)
                                                      values
                                                      (?,?,?,?,?,?,?,?,?)");
               $slqInsertDim->bind_param("sssisssss", $produto->cod_produto, $produto->nome, $produto->uni_medida, $produto->preco);
               $slqInsertDim->execute();
               $sumario->setQuantidadeInclusoes();
            }
            $sqlComercial->close();
            $sqlDim->close();
            $slqInsertDim->close();
            $connComercial->close();
            $connDimensao->close();
         }
      }else{//Dimensão já contém dados
         $sqlComercial = $connComercial->prepare('select*from produto');
         $sqlComercial->execute();
         $resultComercial = $sqlComercial->get_result();
         while($linhaComercial = $resultComercial->fetch_assoc()){
            $sqlDim = $connDimensao->prepare('SELECT SK_produto, cod_produto, nome, uni_medida, preco
                                             FROM
                                             dm_produto
                                             where
                                             cod_produto = ? ');
            $sqlDim->bind_param('s', $linhaComercial['cod_produto']);
            $sqlDim->execute();
            $resultDim = $sqlDim->get_result();
            if($resultDim->num_rows === 0){// O produto da Comercial não está na dimensional
               $sqlInsertDim = $connDimensao->prepare('INSERT INTO dm_cliente
                                                      (cod_produto, nome, uni_medida, preco)
                                                      VALUES
                                                      (?,?,?,?,?,?,?,?,?)');
               $sqlInsertDim->bind_param('sssisssss', $linhaComercial['cod_produto'], $linhaComercial['nome'],
                                          $linhaComercial['uni_medida'],$linhaComercial['preco']);
               $sqlInsertDim->execute();
               if($sqlInsertDim->error){
                  throw new \Exception('Erro: Produto novo não incluso');
               }
               $sumario->setQuantidadeInclusoes();
               
            }
         }
      }
      return $sumario;
   }
   
   private function conectarBanco($banco){
      if(!defined('DS')){
         define('DS', DIRECTORY_SEPARATOR);
      }
      if(!defined('BASE_DIR')){
         define('BASE_DIR', dirname(__FILE__).DS);
      }
      require(BASE_DIR.'config_db.php');
      try{
         $conn = new \MySQLi($dbhost, $user, $password, $banco);
         return $conn;
      }catch(mysqli_sql_exception $e){
         throw new \Exception($e);
         die;
      }
   }
}
?>