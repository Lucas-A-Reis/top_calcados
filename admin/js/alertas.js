const mensagem = document.getElementsByClassName('sumir');

if (mensagem.length > 0) {
    setTimeout(() => {
        mensagem[0].style.opacity = '0';
        setTimeout(() => {
            mensagem[0].remove();
        }, 1000);
    }, 3000);
}


if (window.history.replaceState) {

    const parametros = new URLSearchParams(window.location.search);
    parametros.delete('sucesso');
    parametros.delete('erro');
    const novaQuery = parametros.toString() ? '?' + parametros.toString() : '';
    const url = window.location.pathname + novaQuery;
    window.history.replaceState({}, document.title, url);

}