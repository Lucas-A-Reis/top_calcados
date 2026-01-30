<?php
session_start();
require_once '../checkout/config.php';
require_once '../src/database/conecta.php';
require_once '../src/models/cliente.php';
require_once '../src/services/clienteServico.php';
require_once '../src/models/endereco.php';
require_once '../src/services/enderecoServico.php';
require_once '../src/helpers/funcoes_uteis.php';


$cliente = buscarClientePorId($pdo, $_SESSION['cliente_id']);
$enderecos = listarEnderecos($pdo, $_SESSION['cliente_id']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Top Calçados - Área do Usuário</title>
</head>

<body>
    <?php include '../includes/cabecalho.php'; ?>
    <main style="padding: 10px;">
        <section>
            <div>
                <h1 style="margin-bottom:40px">Olá, <br> <?= $_SESSION['cliente_nome']; ?></h1>
                <div class="card campo-imagem-editar">
                    <div class="titulo_e_icone">
                        <h3>Informações Pessoais</h3>
                        <button id="btn-abrir-modal-editar-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil">
                                <path
                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                                <path d="m15 5 4 4" />
                            </svg>
                        </button>
                    </div>
                    <div class="informacoes">
                        <p><strong>Nome: </strong><?= $cliente['nome']; ?></p>
                        <p><strong>Email: </strong><?= $cliente['email']; ?></p>
                        <p><strong>Telefone: </strong><?= formatarTelefone($cliente['telefone']); ?></p>
                    </div>
                </div>
            </div>
            <div class="card campo-imagem-editar">
                <div class="titulo_e_icone">
                    <h3>Endereços</h3>
                    <button id="btn-abrir-modal">
                        <a style="color:black">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-circle-plus-icon lucide-circle-plus">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M8 12h8" />
                                <path d="M12 8v8" />
                            </svg>
                        </a>
                    </button>
                </div>
                <div class="informacoes">
                    <?php if (empty($enderecos)): ?>
                        <p>Você ainda não tem endereços cadastrados.</p>
                    <?php else: ?>
                        <?php foreach ($enderecos as $endereco): ?>
                            <span style="display: flex; gap:5px; margin-bottom: 5px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-icon lucide-map-pin">
                                    <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                                <p><?= $endereco->getLogradouro() . " " . $endereco->getNumero(); ?></p>
                            </span>
                    <?php endforeach;
                    endif; ?>
                </div>
            </div>
            </div>
            <div>

            </div>
        </section>
        <section></section>
    </main>
    <?php include '../includes/rodape.html'; ?>

    <div id="modal-endereco" class="modal">
        <div class="modal-conteudo">
            <span class="fechar fechar-estilo">&times;</span>
            <h2>Novo Endereço</h2>
            <form action="processar_endereco.php" method="POST">

                <div>
                    <label for="cep">CEP:</label>
                    <input type="text" id="cep" name="cep" placeholder="CEP" required onblur="pesquisarCep(this.value)"
                        oninput="formataCEP(this)" maxlength="9">
                </div>
                <div>
                    <label for="logradouro">Logradouro:</label>
                    <input type="text" id="logradouro" name="logradouro" placeholder="Logradouro" required>
                </div>

                <div>
                    <label for="numero">Numero:</label>
                    <input type="number" id="numero" name="numero" placeholder="Número" required>
                </div>

                <div>
                    <label for="bairro">Bairro:</label>
                    <input type="text" id="bairro" name="bairro" placeholder="Bairro" required>
                </div>

                <div>
                    <label for="cidade">Cidade:</label>
                    <input type="text" id="cidade" name="cidade" placeholder="Cidade" list="cidades" required>
                    <datalist id="cidades">
                    </datalist>
                </div>

                <div>
                    <label for="uf">UF:</label>
                    <select id="uf" name="uf" onchange="carregarCidades(this.value)" required>
                        <option value="">UF</option>
                        <option value="AC">AC</option>
                        <option value="AL">AL</option>
                        <option value="AP">AP</option>
                        <option value="AM">AM</option>
                        <option value="BA">BA</option>
                        <option value="CE">CE</option>
                        <option value="DF">DF</option>
                        <option value="ES">ES</option>
                        <option value="GO">GO</option>
                        <option value="MA">MA</option>
                        <option value="MT">MT</option>
                        <option value="MS">MS</option>
                        <option value="MG">MG</option>
                        <option value="PA">PA</option>
                        <option value="PB">PB</option>
                        <option value="PR">PR</option>
                        <option value="PE">PE</option>
                        <option value="PI">PI</option>
                        <option value="RJ">RJ</option>
                        <option value="RN">RN</option>
                        <option value="RS">RS</option>
                        <option value="RO">RO</option>
                        <option value="RR">RR</option>
                        <option value="SC">SC</option>
                        <option value="SP">SP</option>
                        <option value="SE">SE</option>
                        <option value="TO">TO</option>
                    </select>
                </div>

                <button style="margin-top: 10px;" class="btn_acessar" type="submit">Salvar Endereço</button>
            </form>
        </div>
    </div>
    <div id="modal-info" class="modal">
        <div class="modal-conteudo">
            <span class="fechar-info fechar-estilo">&times;</span>
            <h2>Editar Informações Pessoais</h2>
            <form action="editar_info.php" method="POST">
                <div>
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?= $cliente['nome']; ?>">
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="seuemail@email.com" value="<?= $cliente['email']; ?>">
                </div>
                <div>
                    <label for="telefone">Telefone:</label>
                    <input type="tel" id="telefone" name="telefone" placeholder="(37)99999-0000" value="<?= formatarTelefone( $cliente['telefone']) ; ?>">
                </div>
            </form>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            const modal = document.getElementById("modal-endereco");
            const modalInfo = document.getElementById("modal-info");
            const btn = document.getElementById("btn-abrir-modal");
            const btnInfo = document.getElementById("btn-abrir-modal-editar-info");
            const x = document.querySelector(".fechar");
            const xInfo = document.querySelector(".fechar-info");

            btn.onclick = () => modal.style.display = "block";

            btnInfo.onclick = () => modalInfo.style.display = "block";

            x.onclick = () => modal.style.display = "none";
            xInfo.onclick = () => modalInfo.style.display = "none";

            window.onclick = (event) => {
                if (event.target == modal) modal.style.display = "none";
                if (event.target == modalInfo) modalInfo.style.display = "none";
            }

            function formataCEP(cep_inserido) {
                let valor = cep_inserido.value;

                valor = valor.replace(/\D/g, "");

                if (valor.length > 5) {
                    valor = valor.replace(/^(\d{5})(\d)/, "$1-$2");
                }

                cep_inserido.value = valor;
            }

            function pesquisarCep(valor) {
                const cep = valor.replace(/\D/g, '');

                if (cep !== "" && cep.length === 8) {
                    document.getElementById('logradouro').value = "...";
                    document.getElementById('bairro').value = "...";
                    document.getElementById('cidade').value = "...";

                    fetch(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(res => res.json())
                        .then(dados => {
                            if (!("erro" in dados)) {
                                document.getElementById('logradouro').value = dados.logradouro;
                                document.getElementById('bairro').value = dados.bairro;
                                document.getElementById('cidade').value = dados.localidade;
                                document.getElementById('uf').value = dados.uf;
                                carregarCidades(dados.uf);
                            } else {
                                alert("CEP não encontrado.");
                                limparFormulario();
                            }
                        })
                        .catch(() => alert("Erro ao consultar o CEP."));
                }
            }

            function limparFormulario() {
                document.getElementById('logradouro').value = "";
                document.getElementById('bairro').value = "";
                document.getElementById('cidade').value = "";
                document.getElementById('uf').value = "";
            }

            function carregarCidades(uf) {
                const datalist = document.getElementById('cidades');
                datalist.innerHTML = '';

                if (!uf) return;

                fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${uf}/municipios?orderBy=nome`)
                    .then(res => res.json())
                    .then(cidades => {
                        cidades.forEach(cidade => {
                            const option = document.createElement('option');
                            option.value = cidade.nome;
                            datalist.appendChild(option);
                        });
                    })
                    .catch(err => console.error("Erro ao carregar cidades do IBGE:", err));
            }

            document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                const urlParams = new URLSearchParams(window.location.search);

                if (urlParams.has('erro')) {
                    Toast.fire({
                        icon: 'error',
                        title: urlParams.get('erro')
                    });
                }

                if (urlParams.has('sucesso')) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Endereço salvo com sucesso!'
                    });
                }

                if (urlParams.has('erro') || urlParams.has('sucesso')) {
                    const novaUrl = window.location.pathname;
                    window.history.replaceState({}, document.title, novaUrl);
                }
            });
        </script>
</body>

</html>