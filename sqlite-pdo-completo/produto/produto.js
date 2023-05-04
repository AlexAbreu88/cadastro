'use strict';

const openModal = () => document.getElementById('modal')
    .classList.add('active');

const closeModal = () => {
    // clearFields();
    document.getElementById('modal').classList.remove('active');
};

const getLocalStorage = () => JSON.parse(localStorage.getItem('db_produto')) ?? [];

const setLocalStorage = (dbClient) => localStorage.setItem("db_produto", JSON.stringify(dbClient));

// CRUD - create read update delete
const deleteRegistro = (index) => {
    const dbClient = readDatabase();
    dbClient.splice(index, 1);
    setLocalStorage(dbClient);
};

const updateRegistro = (index, client) => {
    const dbClient = readDatabase();
    dbClient[index] = client;
    setLocalStorage(dbClient);
};

const readDatabase = () => getLocalStorage();

const createRegistro = (client) => {
    const dbClient = readDatabase();
    dbClient.push (client);
    setLocalStorage(dbClient)
};

const isValidFields = () => {
    return document.getElementById('form').reportValidity()
};

//Interação com o layout

const clearFields = () => {
    const fields = document.querySelectorAll('.modal-field');
    fields.forEach(field => field.value = "");
    document.getElementById('descricao').dataset.index = 'new'
};

const saveRegistro = () => {
    if (isValidFields()) {
        const index = document.getElementById('descricao').dataset.index;
        if (index == 'new') {
            const produto = {
                descricao: document.getElementById('descricao').value,
                estoque: document.getElementById('estoque').value,
                precocusto: document.getElementById('precocusto').value,
                precovenda: document.getElementById('precovenda').value
            };
            createRegistro(produto);

            updateRegistroAjax(produto, "INCLUSAO");
        } else {
            const produto = {
                id:document.getElementById('id').value,
                descricao: document.getElementById('descricao').value,
                estoque: document.getElementById('estoque').value,
                precocusto: document.getElementById('precocusto').value,
                precovenda: document.getElementById('precovenda').value
            };
            updateRegistro(index, produto);

            updateRegistroAjax(produto, "ALTERACAO");
        }

        updateTable();

        closeModal();
    }
};

const createRow = (produto, index) => {
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td>${produto.id}</td>
        <td>${produto.descricao}</td>
        <td>${produto.estoque}</td>
        <td>${produto.precocusto}</td>
        <td>${produto.precovenda}</td>
        <td>
            <button type="button" class="button green" id="edit-${index}">Editar</button>
            <button type="button" class="button red" id="delete-${index}" >Excluir</button>
        </td>
    `;
    document.querySelector('#tableClient>tbody').appendChild(newRow);
};

const clearTable = () => {
    const rows = document.querySelectorAll('#tableClient>tbody tr');
    rows.forEach(row => row.parentNode.removeChild(row));
};

const updateTable = () => {
    const dbClient = readDatabase();
    clearTable();
    dbClient.forEach(createRow);
};

const fillFields = (produto) => {
    document.getElementById('id').value = produto.id;
    document.getElementById('descricao').value = produto.descricao;
    document.getElementById('estoque').value = produto.estoque;
    document.getElementById('precocusto').value = produto.precocusto;
    document.getElementById('precovenda').value = produto.precovenda;
    document.getElementById('descricao').dataset.index = produto.index;
};

const editRegistro = (index) => {
    const client = readDatabase()[index];
    client.index = index;
    fillFields(client);
    openModal()
};

const editDelete = (event) => {
    if (event.target.type == 'button') {

        const [action, index] = event.target.id.split('-');

        if (action == 'edit') {
            editRegistro(index)
        } else {
            const produto = readDatabase()[index];
            const response = confirm(`Deseja realmente excluir o produto ${produto.descricao}`);
            if (response) {
                loadAjaxUpdateRegistro (produto, "EXCLUSAO");

                deleteRegistro(index);

                updateTable()
            }
        }
    }
};

// Funcoes AJAX
function loadAjaxConsulta (funcao){
    var oDados = {"funcao":funcao};
    $.ajax({
        url:"ajax_produto.php",
        type:"POST",
        async:true,
        data: oDados,
        success:function(response){
            const aDados = JSON.parse(response);
            aDados.forEach(loadRegistros);
        }
    })
}

function loadAjaxUpdateRegistro (produto, acao){

    var oDados = {"funcao":"loadAjaxUpdateRegistro", "produto": JSON.stringify(produto), "acao" : acao};

    $.ajax({
        url:"ajax_produto.php",
        type:"POST",
        async:true,
        data: oDados,
        success:function(response){
            consultarDados();
        }
    })
}

function loadRegistros(Client, Key){
    const dbClient = Client;

    createRegistro(dbClient);

    updateTable();

    console.log("Client(AJAX):" + JSON.stringify(dbClient));
}

const limpaDados = () => {
    localStorage.setItem("db_produto", "[]");

    updateTable()
};

const consultarDados = () => {
    localStorage.setItem("db_produto", "[]");

    loadAjaxConsulta("listarRegistros");
};

const updateRegistroAjax = (Client, acao) => {
    localStorage.setItem("db_produto", "[]");

    loadAjaxUpdateRegistro (Client, acao);
};

// Eventos
document.getElementById('cadastrarProduto')
.addEventListener('click', openModal);

document.getElementById('modalClose')
.addEventListener('click', closeModal);

document.getElementById('salvar')
.addEventListener('click', saveRegistro);

document.querySelector('#tableClient>tbody')
.addEventListener('click', editDelete);

document.getElementById('cancelar')
.addEventListener('click', closeModal);

document.getElementById('limparDados')
.addEventListener('click', limpaDados);

document.getElementById('consultarDados')
.addEventListener('click', consultarDados);


