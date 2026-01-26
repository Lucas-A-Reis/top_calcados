<header id="cabecalho_admin">
    <?php
    $arquivo_atual = basename($_SERVER['PHP_SELF']);
    $titulo = ucwords(str_replace('_', ' ', basename($arquivo_atual, '.php')));
    echo "<h1>Painel Administrativo - " . $titulo . "</h1>";
    ?>
    <nav id="nav_admin">
        <ul>
            <?php
            $links = [
                'calcados_gerenciar_modelos.php' => 'Calçados',
                'clientes.php' => 'Clientes',
                'historico.php' => 'Histórico'
            ];

            foreach ($links as $url => $label):
                $classe_ativa = (temPalavraEmComum($titulo, $label)) ? 'class="nav-ativo"' : '';
                ?>
                <li><a href="<?php echo $url; ?>" <?php echo $classe_ativa; ?>><?php echo $label; ?></a></li>
            <?php endforeach; ?>

            <li><a href="logout.php">Sair</a></li>
        </ul>
    </nav>
</header>