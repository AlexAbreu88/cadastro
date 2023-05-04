<?php

require_once 'ConsultaPadrao.php';
class ConsultaContato extends ConsultaPadrao {

    protected function getNomeTabela(){
        return "contato";
    }

    protected function getColunasTabela(){
        $html_colunas_tabela = '';

        // busca os dados do banco de dados
        $aDados = $this->getDados();

        if(count($aDados)){
            foreach ($aDados as $aContato){
                // inicia linha
                $html_colunas_tabela .= "<tr>";

                $contato_id = $aContato["contato_id"];
                $nome       = $aContato["nome"];
                $sobrenome  = $aContato["sobrenome"];
                $endereco   = $aContato["endereco"];
                $telefone   = $aContato["telefone"];
                $email      = $aContato["email"];
                $nascimento = $aContato["nascimento"];

                // Colunas
                $html_colunas_tabela .= "<td>$contato_id</td>";
                $html_colunas_tabela .= "<td>$nome</td>";
                $html_colunas_tabela .= "<td>$sobrenome</td>";
                $html_colunas_tabela .= "<td>$endereco</td>";
                $html_colunas_tabela .= "<td>$telefone</td>";
                $html_colunas_tabela .= "<td>$email</td>";
                $html_colunas_tabela .= "<td>$nascimento</td>";

                // Adiciona as acoes da tabela
                $html_colunas_tabela .= $this->getAcoesContato($contato_id);

                // finaliza linha
                $html_colunas_tabela .= "</tr>";
            }
        } else {
            // Retorna uma coluna vazia
            $html_colunas_tabela .= "<tr><td colspan=\"7\" align='center'>Sem dados para exibir</td></tr>";
        }

        return $html_colunas_tabela;
    }

    protected function getAcoesContato($contato_id){
        $html_acao = '<td>
                        <button type="button" class="button green" onclick="editarContato(' . $contato_id . ')">Editar</button>
                   </td>
                   <td>
                        <button type="button" class="button red" onclick="excluirContato(' . $contato_id . ')">Excluir</button>
                    </td>';

        return $html_acao;
    }

    protected function getModalDados(){
        return '<div class="modal" id="modal">
            <div class="modal-content">
                <header class="modal-header">
                    <h2>Novo Contato</h2>
                    <span class="modal-close" id="modalClose">&#10006;</span>
                </header>
                <form id="form" class="modal-form">
                    <input type="hidden" id="contato_id" class="modal-field" placeholder="Id">
                    <input type="text" id="nome" data-index="new" class="modal-field" placeholder="Nome do Cliente" required value="Joao">
                    <input type="text" id="sobrenome" class="modal-field" placeholder="Sobrenome..." required value="da Silva">
                    <input type="text" id="endereco" class="modal-field" placeholder="Endereço..." required value="Estrada Porto Rico">
                    <input type="text" id="telefone" class="modal-field" placeholder="Telefone..." required value="(47)98854-7844">
                    <input type="email" id="email" class="modal-field" placeholder="E-mail..." required value="joao@email.com">
                    <input type="text" id="nascimento" class="modal-field" placeholder="Data Nascimento..." required value="1986/07/19">
                </form>
                <footer class="modal-footer" id="modal-footer">
                    <button id="salvar" class="button green">Salvar</button>
                    <button id="cancelar" class="button blue">Cancelar</button>
                </footer>
            </div>
        </div>';
    }

    protected function getColunasCabecalhoTabela(){
        $html_tabela = "    <th>Id</th>";
        $html_tabela .= "    <th>Nome</th>";
        $html_tabela .= "    <th>Sobrenome</th>";
        $html_tabela .= "    <th>Endereco</th>";
        $html_tabela .= "    <th>Telefone</th>";
        $html_tabela .= "    <th>E-mail</th>";
        $html_tabela .= "    <th>Nascimento</th>";
        $html_tabela .= "    <th colspan='2'>Ações</th>";

        return $html_tabela;
    }
    
    // NOVA PROGRAMACAO FILTROS
    protected function getColunasConsultaTabela(){
        return array("contato_id", "nome", "sobrenome", "endereco", "telefone", "email", "nascimento");
    }
}
// 187 linhas
new ConsultaContato();
