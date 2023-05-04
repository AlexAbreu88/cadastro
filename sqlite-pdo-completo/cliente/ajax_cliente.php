<?php

function listarRegistros(){
    $aDados = getDadosFromBancoDados();
    
    echo json_encode($aDados);
}

function loadAjaxUpdateRegistro(){
    
    $registro = json_decode($_POST["cliente"], true);
    
    $acao = $_POST["acao"];
    
    if($acao == "ALTERACAO"){
        alterarCliente($registro);
    } else if($acao == "INCLUSAO"){
        incluirCliente($registro);
    } else if($acao == "EXCLUSAO"){
        excluirCliente($registro);
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

function excluirCliente($registro){
    /** @var PDO $pdo */
    $pdo = getPdoConnection();
    
    $query = "DELETE FROM `cliente` WHERE `cliente_id` = :cliente_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':cliente_id', $registro["id"], PDO::PARAM_INT);
    $stmt->execute();
}

function incluirCliente($registro){
    /** @var PDO $pdo */
    $pdo = getPdoConnection();
    
    $query = "INSERT INTO `cliente` (nome, telefone, email, cidade)
                  VALUES(:nome, :telefone, :email, :cidade)";
    
    $stmt = $pdo->prepare($query);
    
    $stmt = setParam($stmt, $registro);
    
    $stmt->execute();
}

function alterarCliente($registro){
    /** @var PDO $pdo */
    $pdo = getPdoConnection();
    
    $query = " UPDATE `cliente` SET
                      `nome`     = :nome,
                      `telefone` = :telefone,
                      `email`    = :email,
                      `cidade`   = :cidade
                WHERE `cliente_id` = :cliente_id";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':contato_id', $registro['id']);
    
    $stmt = setParam($stmt, $registro);
    
    $stmt->execute();
}

function setParam($stmt, $registro){
    
    $stmt->bindParam(':nome', $registro['nome']);
    $stmt->bindParam(':telefone', $registro['celular']);
    $stmt->bindParam(':email', $registro['email']);
    $stmt->bindParam(':cidade', $registro['cidade']);
    
    return $stmt;
}

function getDadosFromBancoDados(){
    $pdo = getPdoConnection();
    
    $query = "SELECT cliente_id as id, nome, telefone as celular, email, cidade FROM `cliente`";
    
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
