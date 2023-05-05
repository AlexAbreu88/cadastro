function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
}

// Feche o menu suspenso se o usuário clicar fora dele
window.onclick = function (e) {
    if (!e.target.matches('.dropbtn')) {
        var myDropdown = document.getElementById("myDropdown");
        if (myDropdown.classList.contains('show')) {
            myDropdown.classList.remove('show');
        }
    }
}

// Obtém o link "Home"
const homeLink = document.querySelector('#home-link');

// Adiciona um evento de clique ao link "Home"
homeLink.addEventListener('click', (event) => {
    // Oculta todas as seções
    const secoes = document.querySelectorAll('section');
    secoes.forEach((secao) => {
        secao.style.display = 'none';
    });

    // Mostra apenas a seção "Home"
    const home = document.querySelector('#home');
    home.style.display = 'block';
});

// Obtém os links do menu dropdown
const links = document.querySelectorAll('#myDropdown a');

// Adiciona um evento de clique a cada link
links.forEach((link) => {
    link.addEventListener('click', (event) => {
        // Obtém o valor do atributo data-section do link clicado
        const secaoSelecionada = event.target.getAttribute('data-section');

        // Oculta todas as seções
        const secoes = document.querySelectorAll('section');
        secoes.forEach((secao) => {
            secao.style.display = 'none';
        });

        // Mostra apenas a seção selecionada
        const secao = document.querySelector(`#${secaoSelecionada}`);
        secao.style.display = 'block';
    });
});