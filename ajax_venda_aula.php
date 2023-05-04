<?php

require_once ("conexao.php");
function listarRegistros(){
    $aDados = getDadosFromBancoDados();
    
    echo json_encode($aDados);
}

function loadAjaxUpdateRegistro(){
    
    $registro = json_decode($_POST["venda"], true);
    
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
    $pdo = getConexao();
    
    return $pdo;
}

function excluir($registro){
    /** @var PDO $pdo */
    $pdo = getPdoConnection();
    
    $query = "DELETE FROM `venda` WHERE `venda_id` = :venda_id";
    
    $stmt = $pdo->prepare($query);
    
    $stmt->bindParam(':venda_id', $registro["id"], PDO::PARAM_INT);
    
    $stmt->execute();
}

function incluir($registro){
    /** @var PDO $pdo */
    $pdo = getPdoConnection();
    
    $query = "INSERT INTO `venda` (cliente_id, formapagamento, total)
                  VALUES(:cliente_id, :formapagamento, :total)";
    
    $stmt = $pdo->prepare($query);
    
    $stmt = setParam($stmt, $registro);
    
    $stmt->execute();
}

function alterar($registro){
    /** @var PDO $pdo */
    $pdo = getPdoConnection();
    
    $query = " UPDATE `venda` SET
                      `cliente_id`     = :cliente_id,
                      `formapagamento` = :formapagamento,
                      `total`          = :total
                WHERE `venda_id`       = :venda_id";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':venda_id', $registro['id']);
    
    $stmt = setParam($stmt, $registro);
    
    $stmt->execute();
}

function setParam($stmt, $registro){
    $stmt->bindParam(':cliente_id', $registro['cliente']);
    $stmt->bindParam(':formapagamento', $registro['formapagamento']);
    $stmt->bindParam(':total', $registro['total']);
    return $stmt;
}

function getDadosFromBancoDados(){
    /** @var PDO $pdo */
    $pdo = getPdoConnection();
    
    $query = "SELECT venda_id as id,
                     cliente_id as cliente,
                     formapagamento,
                     total
                FROM `venda`";
    
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

