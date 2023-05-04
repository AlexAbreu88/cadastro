function onLoadLogin() {
    const token_logado = sessionStorage.getItem("token_logado");
    if (token_logado == null) {
        // redireciona para a pagina home pois usuario ja esta logado
        window.location.href = "menu.php";
    }
}

function sairSistema() {
    // função Logout
    // Remove o token da sessao
    sessionStorage.removeItem("token_logado");

    // Remove all saved data from sessionStorage
    sessionStorage.clear();

    // redireciona para a pagina de login
    window.location.href = "index.php";
}

function login() {
    // chama a api de login
    var metodo = "POST";
    var porta = "login";

    const email = document.querySelector("#login-email").value;
    const senha = document.querySelector("#login-senha").value;

    const body = {
        usuemail: email,
        ususenha: senha
    };

    callApi(metodo, porta, body, function (data) {
        if (data.dadoslogin.login) {
            const nome = data.dadoslogin.usunome;
            const status = data.dadoslogin.usuativo;

            // pega os dados de token retornados e seta na sessao do navegador
            sessionStorage.setItem("token_logado", data.dadoslogin.token);
            sessionStorage.setItem("usuario_logado", nome);

            if (status != 0) {
                window.location.href = "menu.php";
            } else {
                alert("Usuário " + nome + " não está autorizado a efetuar login");
                window.location.href = "index.php";
            }
        } else {
            alert("Usuario ou senha invalido!");
        }
    });
}

function gravaRegistroLogin() {
    // chama a api de cadastro de login
    var metodo = "POST";
    var porta = "gravarusuario";

    const nome = document.querySelector("#cadastro-nome").value;
    const email = document.querySelector("#cadastro-email").value;
    const senha = document.querySelector("#cadastro-senha").value;

    const body = {
        usunome: nome,
        usuemail: email,
        ususenha: senha,
        usutoken: "token",
        usuativo: 0
    };

    callApi(metodo, porta, body, function (data) {
        // pega os dados de token retornados e seta na sessao do navegador
        sessionStorage.setItem("token_logado", data.usutoken);
        sessionStorage.setItem("email", data.usuemail);
        console.log(email);

        if (data.usucodigo >= 0) {
            console.log("Usuario já está registrado pelo ID nº" + data.usucodigo);
            alert("O Usuário com o email " + data.usuemail +
                " já está registrado no banco de dados");
        } else {
            enviarEmailGmail(data.usutoken);
            console.log("Se chegou aqui significa que consegui evitar o nº" + data.usucodigo);
        }
    });
}

function resetsenha() {

    var metodo = "POST";
    var porta = "resetpassword";


    const email = document.querySelector("#login-email").value;
    const senha = document.querySelector("#login-senha").value;
    const senha2 = document.querySelector("#login-senha2").value;

    if (senha == "" || senha2 == "") {
        alert("Informe os dois campos de senha!");
        return false;
    }

    if (senha !== senha2) {
        alert("Senha não confere!");
        return false;
    }
    const body = {
        usuemail: email,
        ususenha: senha
    };

    callApi(metodo, porta, body, function (data) {
        // Remove all saved data from sessionStorage
        sessionStorage.clear();

        // redireciona para a pagina de login
        window.location.href = "index.php";
    });
}

function enviarEmailGmail(tokenLogado) {
    var metodo = "POST";
    var porta = "enviaemail";

    // url de ativacao da api
    var ativacao = getUrlBase("ativausuario/"+ tokenLogado) ;

    // Pega o valor pelo id
    const email = document.querySelector("#cadastro-email").value;
    const assunto = "Autentique seu Usuário no Sistema";
    const mensagem = "Para autenticar seu Usuário Por favor copie a url abaixo e abra em seu navegador "
        + ativacao;

    var body = {
        email: email,
        assunto: assunto,
        mensagem: mensagem
    };
    callApi(metodo, porta, body, function (data) {
        // Carrega os dados do usuario na tabela html
        alert(data.mensagem);
    });
}
