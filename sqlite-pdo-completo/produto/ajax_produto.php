<?php

function listarRegistros(){
    $aDados = getDadosFromBancoDados();
    
    echo json_encode($aDados);
}

function loadAjaxUpdateRegistro(){
    
    $registro = json_decode($_POST["produto"], true);
    
    $acao = $_POST["acao"];

    if($acao == "ALTERACAO"){
        alterar($registro);
    } else if($acao == "INCLUSAO"){
        incluir($registro);
    } else if($acao == "EXCLUSAO"){
        excluir($registro);
    } else {
        echo json_encode("Ação invalida!");
    }
}

function getPdoConnection(){
    require_once '../ConexaoSQLitePadrao.php';
    
    $conexaoSQLite = new ConexaoSQLitePadrao;
    
    $pdo = $conexaoSQLite->getPdoConnection();
    
    return $pdo;
}

function excluir($registro){
    /** @var PDO $pdo */
    $pdo = getPdoConnection();
    
    $query = "DELETE FROM `produto` WHERE `produto_id` = :produto_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':produto_id', $registro["id"], PDO::PARAM_INT);
    $stmt->execute();
}

function incluir($registro){
    /** @var PDO $pdo */
    $pdo = getPdoConnection();
    
    $query = "INSERT INTO `produto` (descricao, estoque, precocusto, precovenda)
                  VALUES(:descricao, :estoque, :precocusto, :precovenda)";
    
    $stmt = $pdo->prepare($query);
    
    $stmt = setParam($stmt, $registro);
    
    $stmt->execute();
}

function alterar($registro){
    /** @var PDO $pdo */
    $pdo = getPdoConnection();
    
    $query = " UPDATE `produto` SET
                      `descricao`  = :descricao,
                      `estoque`    = :estoque,
                      `precocusto` = :precocusto,
                      `precovenda` = :precovenda
        WHERE `produto_id` = :produto_id";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':produto_id', $registro['id']);
    
    $stmt = setParam($stmt, $registro);
    
    $stmt->execute();
}

function setParam($stmt, $registro){
    $stmt->bindParam(':descricao', $registro['descricao']);
    $stmt->bindParam(':estoque', $registro['estoque']);
    $stmt->bindParam(':precocusto', $registro['precocusto']);
    $stmt->bindParam(':precovenda', $registro['precovenda']);
    return $stmt;
}

function getDadosFromBancoDados(){
    /** @var PDO $pdo */
    $pdo = getPdoConnection();
    
    $query = "SELECT produto_id as id,
                     descricao,
                     estoque,
                     precocusto,
                     precovenda
                FROM `produto`";
    
    $stmt = $pdo->prepare($query);
    
    $stmt->execute();
    $aDados = array();
    while($aDadosColuna = $stmt->fetchObject()){
        $aDados[] = $aDadosColuna;
    }
    
    return $aDados;
}

if(isset($_POST["funcao"])){
    $funcao = $_POST["funcao"];
    
    switch ($funcao){
        case "listarRegistros":
            listarRegistros();
        break;
        case "loadAjaxUpdateRegistro":
            loadAjaxUpdateRegistro();
        break;
    }
} else {
    echo json_encode(array("mensagem" => "Funcao invalida!"));
}

