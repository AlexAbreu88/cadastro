function getData(filter) {
    // Cria um objeto XMLHttpRequest
    var xhr = new XMLHttpRequest();
    // Abre uma conexão com o script PHP
    xhr.open('GET', 'getData.php?filter=' + encodeURIComponent(filter));
    // Envia a solicitação
    xhr.send();
    // Quando a solicitação for concluída
    xhr.onload = function() {
        if (xhr.status != 200) {
            alert(`Erro ${xhr.status}: ${xhr.statusText}`);
        } else {
            // Processa os dados retornados pelo script PHP
            var data = JSON.parse(xhr.response);
            // Faz algo com os dados
            console.log(data);
        }
    };
}

function updateData(id, name, email) {
    // Cria um objeto FormData para armazenar os dados
    var formData = new FormData();
    formData.append('id', id);
    formData.append('name', name);
    formData.append('email', email);
    // Cria um objeto XMLHttpRequest
    var xhr = new XMLHttpRequest();
    // Abre uma conexão com o script PHP
    xhr.open('POST', 'updateData.php');
    // Envia os dados
    xhr.send(formData);
    // Quando a solicitação for concluída
    xhr.onload = function() {
        if (xhr.status != 200) {
            alert(`Erro ${xhr.status}: ${xhr.statusText}`);
        } else {
            // Processa a resposta do script PHP
            console.log(xhr.response);
        }
    };
}

function deleteData(id) {
    // Cria um objeto FormData para armazenar os dados
    var formData = new FormData();
    formData.append('id', id);
    // Cria um objeto XMLHttpRequest
    var xhr = new XMLHttpRequest();
    // Abre uma conexão com o script PHP
    xhr.open('POST', 'deleteData.php');
    // Envia os dados
    xhr.send(formData);
    // Quando a solicitação for concluída
    xhr.onload = function() {
        if (xhr.status != 200) {
            alert(`Erro ${xhr.status}: ${xhr.statusText}`);
        } else {
            // Processa a resposta do script PHP
            console.log(xhr.response);
        }
    };
}