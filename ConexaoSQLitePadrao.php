<?php

class ConexaoSQLitePadrao
{

    protected $pdoConection;

    public function __construct()
    {
        $this->conectaBanco();
    }

    protected function conectaBanco()
    {
        try {
            $this->pdoConection = new PDO('sqlite:../db/database.sqlite3');
            $this->pdoConection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }

        $query = "CREATE TABLE IF NOT EXISTS usuario 
        (usuario_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
        nome TEXT, email TEXT, senha TEXT)";
        $this->pdoConection->exec($query);

        $query = "CREATE TABLE IF NOT EXISTS curriculo 
        (curriculo_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
        nome TEXT, email TEXT, cpf TEXT, data_nascimento TEXT, sexo TEXT, 
        estado_civil TEXT, escolaridade TEXT, cursos_especializacoes TEXT, 
        experiencia TEXT, pretensao_salarial TEXT)";
        $this->pdoConection->exec($query);
    }

    public function executaQuery($sql)
    {
        $this->pdoConection->exec($sql);
    }

    public function getPdoConnection()
    {
        return $this->pdoConection;
    }
}
