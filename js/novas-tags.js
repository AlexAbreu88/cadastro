function addEspecializacao() {
    const div = document.querySelector('.cursos_especializacoes');

    // Criar label e input para o título da especialização
    const labelTitulo = document.createElement('label');
    labelTitulo.textContent = 'Título do Curso/Especialização: ';
    const inputTitulo = document.createElement('input');
    inputTitulo.type = 'text';
    div.appendChild(labelTitulo);
    div.appendChild(inputTitulo);

    // Criar label e input para a descrição da especialização
    const labelDescricao = document.createElement('label');
    labelDescricao.textContent = 'Descrição do Curso/Especialização: ';
    const inputDescricao = document.createElement('input');
    inputDescricao.type = 'text';
    div.appendChild(labelDescricao);
    div.appendChild(inputDescricao);

    removeEspecial.disabled = false;
}

function addExperiencia() {
    const div = document.querySelector('.exp_profissional');

    // Criar label e input para o título da experiência
    const labelTitulo = document.createElement('label');
    labelTitulo.textContent = 'Título da Experiência Profissional: ';
    const inputTitulo = document.createElement('input');
    inputTitulo.type = 'text';
    div.appendChild(labelTitulo);
    div.appendChild(inputTitulo);

    // Criar label e input para a descrição da experiência
    const labelDescricao = document.createElement('label');
    labelDescricao.textContent = 'Descrição da Experiência Profissional: ';
    const inputDescricao = document.createElement('input');
    inputDescricao.type = 'text';
    div.appendChild(labelDescricao);
    div.appendChild(inputDescricao);

    removeExp.disabled = false;
}

function removeEspecializacao() {
    const div = document.querySelector('.cursos_especializacoes');
    const inputs = div.querySelectorAll('input');
    if (inputs.length > 1) {
        // Remover input e label da descrição da especialização
        const lastInputDescricao = inputs[inputs.length - 1];
        const lastLabelDescricao = lastInputDescricao.previousSibling;
        div.removeChild(lastInputDescricao);
        div.removeChild(lastLabelDescricao);

        // Remover input e label do título da especialização
        const lastInputTitulo = inputs[inputs.length - 2];
        const lastLabelTitulo = lastInputTitulo.previousSibling;
        div.removeChild(lastInputTitulo);
        div.removeChild(lastLabelTitulo);
    }
    if (div.querySelectorAll('input').length < 2) {
        removeEspecial.disabled = true;
    }
}

function removeExperiencia() {
    const div = document.querySelector('.exp_profissional');
    const inputs = div.querySelectorAll('input');
    if (inputs.length > 1) {
        // Remover input e label da descrição da experiência
        const lastInputDescricao = inputs[inputs.length - 1];
        const lastLabelDescricao = lastInputDescricao.previousSibling;
        div.removeChild(lastInputDescricao);
        div.removeChild(lastLabelDescricao);

        // Remover input e label do título da experiência
        const lastInputTitulo = inputs[inputs.length - 2];
        const lastLabelTitulo = lastInputTitulo.previousSibling;
        div.removeChild(lastInputTitulo);
        div.removeChild(lastLabelTitulo);
    }
    if (div.querySelectorAll('input').length < 2) {
        removeExp.disabled = true;
    }
}