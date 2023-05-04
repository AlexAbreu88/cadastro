<?php

require_once 'ManutencaoPadrao.php';
class ManutencaoCliente extends ManutencaoPadrao {

    protected function getNomeTabela() {
        return "cliente";
    }

    protected function getNomeColunaChave() {
        return "cliente_id";
    }

    // CONTINUAR DAQUI E ATUALIZAR O ARQUIVO contato.js
    protected function buscaDadosAlteracao(){
        $registro = json_decode($_POST["cliente"], true);

        $chave_id = $registro["cliente_id"];

        $aDados = $this->getDadosFromBancoDados($chave_id);

        echo json_encode($aDados);
    }

    protected function executaAlteracao(){
        $registro = json_decode($_POST["cliente"], true);

        /** @var PDO $pdo */
        $pdo = getConexao();

        $query = "UPDATE `cliente` SET
                     `nome`     = :nome,
                     `telefone` = :telefone,
                     `email`    = :email,
                     `cidade`   = :cidade
             WHERE `cliente_id` = :cliente_id";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':nome'      , $registro['nome']);
        $stmt->bindParam(':telefone'  , $registro['telefone']);
        $stmt->bindParam(':email'     , $registro['email']);
        $stmt->bindParam(':cidade'    , $registro['cidade']);
        $stmt->bindParam(':cliente_id', $registro['cliente_id']);

        $stmt->execute();

        $stmt = null;
        $pdo = null;

        echo json_encode($registro);
    }

    protected function executaInclusao(){

        $registro = json_decode($_POST["cliente"], true);

        /** @var PDO $pdo */
        $pdo = getConexao();

        $query = "INSERT INTO `cliente` (nome, telefone, email, cidade)
                    VALUES (:nome, :telefone, :email, :cidade)";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':nome'    , $registro['nome']);
        $stmt->bindParam(':telefone', $registro['telefone']);
        $stmt->bindParam(':email'   , $registro['email']);
        $stmt->bindParam(':cidade'  , $registro['cidade']);

        $stmt->execute();

        $stmt = null;
        $pdo = null;

        echo json_encode($registro);
    }
  
}

// linhas 123
