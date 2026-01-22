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
    const novaUrl = window.location.pathname;
    window.history.replaceState({}, document.title, novaUrl);
}