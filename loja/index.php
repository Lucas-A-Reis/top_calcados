<?php
session_start();
require_once '../checkout/config.php';
require_once  '../src/database/conecta.php';
require_once '../src/models/imagem.php';
require_once '../src/services/imagemServico.php';
require_once '../src/helpers/funcoes_uteis.php';
require_once '../src/models/modelo.php';
require_once '../src/services/modeloServico.php';
require_once '../src/models/variacao.php';
require_once '../src/services/variacaoServico.php';


$modelos = array_filter(listarModelos($pdo), function ($item) {
    return $item->getStatus() === 1;
});

$destaques = array_filter($modelos, function ($item) {
    return $item->getDestaque() === 1;
});


$imagens = buscarImagensPorVariacaoId($pdo, 38);
foreach ($imagens as $imagem) {
    $nomes_dos_arquivos[] = $imagem->getCaminhoArquivo();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Top Calçados</title>
</head>

<body>
    <?php include '../includes/cabecalho.php'; ?>
    <div id="fotos_e_bolinhas">
        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="../media/img/calcados/<?= $nomes_dos_arquivos[0]  ?>" class="d-block w-100" alt="">
                </div>
                <div class="carousel-item">
                    <img src="../media/img/calcados/<?= $nomes_dos_arquivos[1]  ?>" class="d-block w-100" alt="">
                </div>
                <div class="carousel-item">
                    <img src="../media/img/calcados/<?= $nomes_dos_arquivos[2]  ?>" class="d-block w-100" alt="">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <div class="scroll-container">
            <button class="seta-scroll esquerda" onclick="scrollar(this, -200)">&#10094;</button>
            <section id="bolinhas" class="scroll-content">
                <a href=""><img src="../media/img/logo.webp" alt="">
                    <p>Rasteirinhas</p>
                </a>
                <a href=""><img src="../media/img/logo.webp" alt="">
                    <p>Rasteirinhas1</p>
                </a>
                <a href=""><img src="../media/img/logo.webp" alt="">
                    <p>Rasteirinhas2</p>
                </a>
                <a href=""><img src="../media/img/logo.webp" alt="">
                    <p>Rasteirinhas3</p>
                </a>
                <a href=""><img src="../media/img/logo.webp" alt="">
                    <p>Rasteirinhas4</p>
                </a>
                <a href=""><img src="../media/img/logo.webp" alt="">
                    <p>Rasteirinhas5</p>
                </a>
                <a href=""><img src="../media/img/logo.webp" alt="">
                    <p>Rasteirinhas6</p>
                </a>
                <a href=""><img src="../media/img/logo.webp" alt="">
                    <p>Rasteirinhas7</p>
                </a>
                <a href=""><img src="../media/img/logo.webp" alt="">
                    <p>Rasteirinhas8</p>
                </a>
                <a href=""><img src="../media/img/logo.webp" alt="">
                    <p>Rasteirinhas9</p>
                </a>
            </section>
            <button class="seta-scroll direita" onclick="scrollar(this, 200)">&#10095;</button>
        </div>
    </div>
    <section class="cards-section">
        <h2 class="titulo_da_secao">Destaques</h2>
        <div class="scroll-container">
            <button class="seta-scroll esquerda" onclick="scrollar(this, -200)">&#10094;</button>
            <div class="cards scroll-content">
                <?php foreach ($destaques as $destaque):
                    $variacoes_por_modelo = buscarVariacoesPorModelo($pdo, $destaque->getId()); ?>
                    <article class="card-homepage">
                        <div class="imagem-container">
                            <img class="imagem" src=<?= "../media/img/calcados/" . buscarImagensPorVariacaoId($pdo, $variacoes_por_modelo[0]->getId())[0]->getCaminhoArquivo() ?> alt="">
                        </div>
                        <div class="informacoes_do_calcado">
                            <span class="cores">
                                <?php if (count($variacoes_por_modelo) > 1): foreach ($variacoes_por_modelo as $variacao): ?>
                                        <div style="background-color: <?= $variacao->getCorHex() ?>;" class="bolinha-grande" data-id="<?= $variacao->getId() ?>"></div>
                                <?php endforeach;
                                else: echo "<div style='height:20px;'></div>";
                                endif; ?>
                            </span>
                            <h3 class="nome">
                                <?= $destaque->getMarca() . " " . $destaque->getTipo() ?>
                            </h3>
                            <span class="preco">
                                <?= formatarPreco($destaque->getPreco()) ?>
                            </span>
                        </div>
                        <button class="btn-comprar">Comprar</button>
                    </article>
                <?php endforeach ?>
            </div>
            <button class="seta-scroll direita" onclick="scrollar(this, 200)">&#10095;</button>
        </div>
    </section>
    <?php include '../includes/rodape.html'; ?>
    <script>
        const bolinhas = document.querySelectorAll('.bolinha-grande');
        const imagem = document.querySelector('.imagem');

        bolinhas.forEach(bolinha => {
            bolinha.onclick = () => {
                const idVariacao = bolinha.getAttribute('data-id');

                fetch('buscar_imagem.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: idVariacao
                        }),
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Erro na rede');
                        return response.json();
                    })
                    .then(nomeDoArquivo => {
                        if (nomeDoArquivo) {
                            imagem.src = `../media/img/calcados/${nomeDoArquivo}`;

                            imagem.classList.remove('fade-in');
                            void imagem.offsetWidth;
                            imagem.classList.add('fade-in');
                        }
                    })
                    .catch(error => console.error('Erro:', error));
            };
        });

        function scrollar(botao, distancia) {
            const pai = botao.parentElement;

            const scrollavel = pai.querySelector('.scroll-content');

            scrollavel.scrollBy({
                left: distancia,
                behavior: 'smooth'
            });
        }

        function gerenciarSetas(container) {
            const pai = container.parentElement;
            const setaEsquerda = pai.querySelector('.esquerda');
            const setaDireita = pai.querySelector('.direita');

            const scrollEsquerda = container.scrollLeft;
            const larguraTotal = container.scrollWidth;
            const larguraVisivel = container.clientWidth;

            setaEsquerda.style.display = scrollEsquerda > 5 ? "flex" : "none";

            setaDireita.style.display = (scrollEsquerda + larguraVisivel) >= (larguraTotal - 5) ? "none" : "flex";
        }

        document.querySelectorAll('.scroll-content').forEach(section => {
            section.addEventListener('scroll', () => gerenciarSetas(section));

            gerenciarSetas(section);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>