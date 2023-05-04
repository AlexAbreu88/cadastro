<?php

require_once ("ManutencaoPadrao.php");
class ManutencaoContato extends ManutencaoPadrao {

    protected function getNomeTabela() {
        return "contato";
    }

    protected function getNomeColunaChave() {
        return "contato_id";
    }

    protected function buscaDadosAlteracao(){
        $registro = json_decode($_POST["contato"], true);

        $contato_id = $registro["contato_id"];

        $aDados = $this->getDadosFromBancoDados($contato_id);

        echo json_encode($aDados);
    }

    protected function executaAlteracao(){

        $registro = json_decode($_POST["contato"], true);

        /** @var PDO $pdo */
        $pdo = getConexao();

        $query = "UPDATE `contato` SET `nome` = :nome, `sobrenome` = :sobrenome, `endereco` = :endereco, `telefone` = :telefone, `email` = :email, `nascimento` = :nascimento WHERE `contato_id` = :contato_id";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':nome'      , $registro['nome']);
        $stmt->bindParam(':sobrenome' , $registro['sobrenome']);
        $stmt->bindParam(':endereco'  , $registro['endereco']);
        $stmt->bindParam(':telefone'  , $registro['telefone']);
        $stmt->bindParam(':email'     , $registro['email']);
        $stmt->bindParam(':nascimento', $registro['nascimento']);
        $stmt->bindParam(':contato_id', $registro['contato_id']);

        $stmt->execute();

        $stmt = null;
        $pdo = null;

        echo json_encode($registro);
    }

    protected function executaInclusao(){

        $registro = json_decode($_POST["contato"], true);

        require_once 'conexao.php';
        /** @var PDO $pdo */
        $pdo = getConexao();

        $query = "INSERT INTO `contato` (nome, sobrenome, endereco, telefone, email, nascimento)
    VALUES(:nome, :sobrenome, :endereco, :telefone, :email, :nascimento)";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':nome'      , $registro['nome']);
        $stmt->bindParam(':sobrenome' , $registro['sobrenome']);
        $stmt->bindParam(':endereco'  , $registro['endereco']);
        $stmt->bindParam(':telefone'  , $registro['telefone']);
        $stmt->bindParam(':email'     , $registro['email']);
        $stmt->bindParam(':nascimento', $registro['nascimento']);

        $stmt->execute();

        $stmt = null;
        $pdo = null;

        echo json_encode($registro);
    }
}

// 127 linhas
