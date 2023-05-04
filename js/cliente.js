function excluirCliente(cliente_id){
    console.log('cliente id:' + cliente_id);

	alert("Excluindo registro...");

    const cliente = {
        cliente_id: cliente_id
    };
    loadAjaxUpdateRegistro(cliente, "EXECUTA_EXCLUSAO");
}

function loadAjaxUpdateRegistro (oDados, acao){
    var oDados = {
        "cliente": JSON.stringify(oDados),
        "acao" : acao
    };
    $.ajax({
        url:"ajax_cliente_aula.php",
        type:"POST",
        async:true,
        data: oDados,
        success:function(response){
            console.log("dados enviados:" + response);

            // Atualiza a consulta
            loadAjaxConsulta();
        }
    })
}

function loadAjaxConsulta(){
    var oDados = {
            "acao"    : "EXECUTA_CONSULTA",
            "campo"   : document.querySelector("#campo").value,
            "operador": document.querySelector("#operador").value,
            "valor"   : document.querySelector("#valor").value
        };

    $.ajax({
        url:"ajax_cliente_aula.php",
        type:"POST",
        async:true,
        data: oDados,
        success:function(response){

            console.log("Retorno Consulta(AJAX):" + JSON.stringify(response));

            const aDados = JSON.parse(response);


            clearTable();

            aDados.forEach(createRow);
        }
    })
}

const clearTable = () => {
    const rows = document.querySelectorAll('#tableDados>tbody tr');
    rows.forEach(row => row.parentNode.removeChild(row));
};

const createRow = (cliente, index) => {
    const newRow = document.createElement('tr');

    newRow.innerHTML = `
        <td>${cliente.cliente_id}</td>
        <td>${cliente.nome}</td>
        <td>${cliente.telefone}</td>
        <td>${cliente.email}</td>
        <td>${cliente.cidade}</td>
        <td>
            <button type="button" class="button green" onclick="editarCliente(${cliente.cliente_id})">Editar</button>
        </td>
        <td>
            <button type="button" class="button red" onclick="excluirCliente(${cliente.cliente_id})">Excluir</button>
        </td>
    `;

    document.querySelector('#tableDados>tbody').appendChild(newRow);
};

function editarCliente(cliente_id){
    console.log('cliente id:' + cliente_id);

    const cliente = {
        cliente_id: cliente_id
    };

    var oDados = {
        "cliente": JSON.stringify(cliente),
        "acao" : "BUSCA_DADOS_ALTERACAO"
    };

    $.ajax({
        url:"ajax_cliente_aula.php",
        type:"POST",
        async:true,
        data: oDados,
        success:function(response){

            console.log("dados retornados:" + response);

            // Carrega os dados na tela
            const oCliente = JSON.parse(response);

            oCliente.id = oCliente.cliente_id;

            carregaCampos(oCliente);

            // Abre o Modal
            openModal();
        }
    })
}

const carregaCampos = (oCliente) => {
    document.getElementById('cliente_id').value   = oCliente.cliente_id;
    document.getElementById('nome').value         = oCliente.nome;
    document.getElementById('telefone').value     = oCliente.telefone;
    document.getElementById('email').value        = oCliente.email;
    document.getElementById('cidade').value       = oCliente.cidade;
    document.getElementById('nome').dataset.index = oCliente.index;
};

const openModal = () => {
    document.getElementById('modal').classList.add('active');
    document.getElementById('modal-footer').classList.add('active');
};

const closeModal = () => {
    //clearFields();
    document.getElementById('modal').classList.remove('active');
};

const updateDados = () => {
    if (isValidFields()) {
        const index = document.getElementById('nome').dataset.index;
        if (index == 'new') {
            const cliente = {
                nome: document.getElementById('nome').value,
                telefone: document.getElementById('telefone').value,
                email: document.getElementById('email').value,
                cidade: document.getElementById('cidade').value
            };

            loadAjaxUpdateRegistro(cliente, "EXECUTA_INCLUSAO");
        } else {
            const cliente = {
                cliente_id:document.getElementById('cliente_id').value,

                nome: document.getElementById('nome').value,
                telefone: document.getElementById('telefone').value,
                email: document.getElementById('email').value,
                cidade: document.getElementById('cidade').value
            };

            loadAjaxUpdateRegistro(cliente, "EXECUTA_ALTERACAO");
        }

        closeModal();
    }
};

const isValidFields = () => {
    return document.getElementById('form').reportValidity()
};

function incluirDados(){
    document.getElementById('cliente_id').value = "";
    openModal();
}


