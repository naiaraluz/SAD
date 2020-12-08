<?php
namespace dimensoes;

/**
 * Model da entidade produto
 * @author Naiara
 */
class Produto{
    /**
    * Cod do Produto
    * @var int
    */
   public $cod_produto;
   /**
    * Nome do Produto
    * @var string
    */
   public $nome;
   /**
    * Unidade de Medida
    * @var string
    */
   public $uni_medida;
   /**
    * Preco do Produto
    * @var float
    */
   public $preco;
   

   /**
    * Carrega os atributos da classe Produto
    * @param $cod_produto codigo do produto
    * @param $nome nome do produto
    * @param $uni_medida unidade de medida
    * @param $preco preco do produto

    *@return Void
    */

   public function setProduto($nome, $uni_medida, $preco, $cod_produto){
      $this->cod_produto = $cod_produto;
      $this->nome = $nome;
      $this->uni_medida = $uni_medida;
      $this->preco = $preco;

   }
}
?>