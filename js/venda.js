'use strict';

const openModal = () => document.getElementById('modal')
    .classList.add('active');

const closeModal = () => {
    //clearFields();
    document.getElementById('modal').classList.remove('active');
};

const getLocalStorage = () => JSON.parse(localStorage.getItem('db_venda')) ?? [];

const setLocalStorage = (dbClient) => localStorage.setItem("db_venda", JSON.stringify(dbClient));

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
    document.getElementById('id').dataset.index = 'new'
};

const saveRegistro = () => {
    if (isValidFields()) {
        const index = document.getElementById('id').dataset.index;
        if (index == 'new') {
            const venda = {
                cliente: document.getElementById('cliente').value,
                formapagamento: document.getElementById('formapagamento').value,
                total: document.getElementById('total').value
            };
            createRegistro(venda);

            updateRegistroAjax(venda, "INCLUSAO");
        } else {
            const venda = {
                id:document.getElementById('id').value,
                cliente: document.getElementById('cliente').value,
                formapagamento: document.getElementById('formapagamento').value,
                total: document.getElementById('total').value
            };
            updateRegistro(index, venda);

            updateRegistroAjax(venda, "ALTERACAO");
        }

        updateTable();
        closeModal();
    }
};

const createRow = (venda, index) => {
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td>${venda.id}</td>
        <td>${venda.cliente}</td>
        <td>${venda.formapagamento}</td>
        <td>${venda.total}</td>
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

const fillFields = (venda) => {
    document.getElementById('id').value             = venda.id;
    document.getElementById('cliente').value        = venda.cliente;
    document.getElementById('formapagamento').value = venda.formapagamento;
    document.getElementById('total').value          = venda.total;
    document.getElementById('id').dataset.index     = venda.index;
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
            const venda = readDatabase()[index];
            const response = confirm(`Deseja realmente excluir a venda ${venda.id}`);
            if (response) {
                loadAjaxUpdateRegistro (venda, "EXCLUSAO");

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
        url:"ajax_venda_aula.php",
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

    var oDados = {"funcao":"loadAjaxUpdateRegistro", "venda": JSON.stringify(produto), "acao" : acao};

    $.ajax({
        url:"ajax_venda_aula.php",
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
    localStorage.setItem("db_venda", "[]");

    updateTable()
};

const consultarDados = () => {
    localStorage.setItem("db_venda", "[]");

    loadAjaxConsulta("listarRegistros");
};

const updateRegistroAjax = (Client, acao) => {
    localStorage.setItem("db_venda", "[]");

    loadAjaxUpdateRegistro (Client, acao);
};

// Eventos
document.getElementById('cadastrarVenda')
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


