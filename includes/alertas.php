<?php if (isset($_GET['sucesso'])): ?>
    <p style="transition: opacity 1s ease; margin-top: 20px;" class="alerta-sucesso sumir">
        <?php if ($_GET['sucesso'] == 1) {
            echo $nome . "cadastrado com sucesso!";
        } elseif ($_GET['sucesso'] == 2) {
            echo $nome . "editado com sucesso!";
        } ?>
    </p>
<?php endif; ?>

<?php if (isset($_GET['erro'])): ?>
    <p style="transition: opacity 1s ease; margin-top: 20px;" class="alerta-erro sumir"> Erro ao editar o <?= $nome; ?>.</p>
<?php endif; ?>