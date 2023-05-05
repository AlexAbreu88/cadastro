var nome = document.getElementById("nome");
var email = document.getElementById("e-mail");
function getData() {
    // Cria um objeto XMLHttpRequest
    var xhr = new XMLHttpRequest();
    // Abre uma conexão com o script PHP
    xhr.open('GET', 'getData.php');
    // Envia a solicitação
    xhr.send();
    // Quando a solicitação for concluída
    xhr.onload = function () {
        if (xhr.status != 200) {
            alert(`Erro ${xhr.status}: ${xhr.statusText}`);
        } else {
            // Processa os dados retornados pelo script PHP
            var data = JSON.parse(xhr.response);
            // Faz algo com os dados
            // console.log(data);
            // console.log(data[0].name);
            // console.log(data[0].email);
            // Atualiza o conteúdo dos elementos span com os dados
            nome.textContent = data[0].name;
            email.textContent = data[0].email;
        }
    };
}