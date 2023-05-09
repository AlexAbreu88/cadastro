//Declaração de Variáveis
const idUsuarioLogado = document.querySelector('#user_id').value;
const nomeUsuarioLogado = document.querySelector('#user_username').value;
const diaLocal = document.querySelector('#current_date');
var usuarioLogado = document.querySelector('#usuario_logado');

function consultarDados() {
    // Define um array de nomes de colunas e valores de ordem
    const colunas = ['', 'id', 'titulo', 'escolaridade', 'pretensao_salarial', 'dia_local', 'user_id'];
    const ordens = ['', 'ASC', 'ASC', 'DESC'];
    // Obtém os valores dos elementos HTML
    const coluna = colunas[document.querySelector('#coluna-tabela').value];
    const ordem = ordens[document.querySelector('#classificar-tabela').value];
    const valor = document.querySelector('#valor').value;
    // Cria um objeto XMLHttpRequest
    const xhr = new XMLHttpRequest();
    // Abre uma conexão com o script PHP
    xhr.open('POST', 'consulta_tabela.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    // Envia a solicitação com os parâmetros
    xhr.send(`coluna=${coluna}&ordem=${ordem}&valor=${valor}`);
    // Quando a solicitação for concluída
    xhr.onload = function () {
        if (xhr.status != 200) {
            alert(`Erro ${xhr.status}: ${xhr.statusText}`);
        } else {
            // Processa os dados retornados pelo script PHP
            var data = JSON.parse(xhr.response);
            // Limpa o corpo da tabela
            const tbody = document.querySelector('.corpo-tabela');
            tbody.innerHTML = '';
            // Adiciona os resultados da consulta à tabela
            let soma = 0;
            for (let i = 0; i < data.length; i++) {
                // Cria um elemento tr
                let tr = document.createElement('tr');
                // Adiciona as células à linha
                tr.innerHTML = `
                    <td>${data[i].id}</td>
                    <td>${data[i].titulo}</td>
                    <td>${data[i].escolaridade}</td>
                    <td class="pretensao-salarial">${data[i].pretensao_salarial}</td>
                    <td>${data[i].dia_local}</td>
                    <td>${data[i].user_id}</td>
                `;
                // Adiciona a linha ao corpo da tabela
                tbody.appendChild(tr);
                // Acumula a soma dos valores da coluna pretensao_salarial
                soma += data[i].pretensao_salarial;
            }
            // Calcula a média dos valores da coluna pretensao_salarial
            let media = soma / data.length;
            // Atualiza o conteúdo do elemento span
            document.querySelector('#media-proposta-salarial').textContent = media.toFixed(2);
            document.querySelector('#soma-proposta-salarial').textContent = soma;
            // Pinta as células da coluna pretensao_salarial de verde ou azul
            const cells = document.querySelectorAll('.pretensao-salarial');
            for (let i = 0; i < cells.length; i++) {
                let valor = parseFloat(cells[i].textContent);
                if (valor < media) {
                    cells[i].classList.add('verde');
                } else if (valor > media) {
                    cells[i].classList.add('azul');
                }
            }
        }
    };
}

function limparDados() {

}

function carregaNome() {
    usuarioLogado.innerHTML = nomeUsuarioLogado;
}

// Chamar a função buscarCurriculo ao carregar a página
window.addEventListener('load', carregaNome);
