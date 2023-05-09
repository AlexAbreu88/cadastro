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
// Obtém o link "Cadastro"
const cadastroLink = document.querySelector('#cadastro-link');
// Adiciona um evento de clique ao link "Cadastro"
cadastroLink.addEventListener('click', (event) => {
  // Oculta todas as seções
  const secoes = document.querySelectorAll('section');
  secoes.forEach((secao) => {
    secao.style.display = 'none';
  });
  // Mostra apenas a seção "Cadastro"
  const cadastro = document.querySelector('#cadastro');
  cadastro.style.display = 'block';
});