<?php

class ConexaoSQLitePadrao {
    
    protected $pdoConection;
    
    public function __construct() {
        $this->conectaBanco();
    }
    
    protected function conectaBanco(){
        try {
            $this->pdoConection = new PDO('sqlite:../db/database.sqlite3');
            $this->pdoConection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    
        $query = "CREATE TABLE IF NOT EXISTS contato (contato_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nome TEXT, sobrenome TEXT, endereco TEXT, telefone TEXT, email TEXT, nascimento TEXT)";
        $this->pdoConection->exec($query);
    
        $query = "CREATE TABLE IF NOT EXISTS cliente (cliente_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nome TEXT, telefone TEXT, email TEXT, cidade TEXT)";
        $this->pdoConection->exec($query);
        
        $query = "CREATE TABLE IF NOT EXISTS produto (produto_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, descricao TEXT,estoque INTEGER, precocusto REAL, precovenda REAL)";
        $this->pdoConection->exec($query);
        
        $query = "CREATE TABLE IF NOT EXISTS venda (venda_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, cliente_id INTEGER, formapagamento TEXT, total REAL)";
        $this->pdoConection->exec($query);
    }
    
    public function executaQuery($sql){
        $this->pdoConection->exec($sql);
    }
    
    public function getPdoConnection(){
        return $this->pdoConection;
    }
}
