function logout() {
    // Cria um objeto XMLHttpRequest
    var xhr = new XMLHttpRequest();
    // Abre uma conexão com o script PHP
    xhr.open('GET', 'logout.php');
    // Envia a solicitação
    xhr.send();
    // Quando a solicitação for concluída
    xhr.onload = function () {
        if (xhr.status != 200) {
            alert(`Erro ${xhr.status}: ${xhr.statusText}`);
        } else {
            // debugger;
            // Processa a resposta do script PHP
            console.log(xhr.response);
            setTimeout(function () {
                parent.location.href = 'index.php#home';
            }, 100);
        }
    };
}