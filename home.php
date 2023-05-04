<?php

require_once("ConsultaPadrao.php");
class Home extends ConsultaPadrao
{

    protected function getColunasTabela()
    {
        // TODO: Implement getColunasTabela() method.
    }

    protected function getModalDados()
    {
        // TODO: Implement getModalDados() method.
    }

    protected function getColunasCabecalhoTabela()
    {
        // TODO: Implement getColunasCabecalhoTabela() method.
    }

    protected function getAcoesInclusao()
    {
        return "";
    }

    protected function getNomeTabela()
    {
        return 'Bem vindo!<br> sistema de compras!';
    }

    protected function getColunasConsultaTabela()
    {
        // TODO: Implement getColunasConsultaTabela() method.
    }

    protected function getFiltrosConsulta()
    {
        return "";
    }

    protected function getScriptFooter()
    {
        return '';
    }
}

new Home();
