<?php if (isset($_GET['sucesso'])): ?>
    <p style="transition: opacity 1s ease; margin-top: 20px;" class="alerta-sucesso sumir">
        <?php if ($_GET['sucesso'] == 1) {
            echo $nome . "cadastrado com sucesso!";
        } elseif ($_GET['sucesso'] == 2) {
            echo $nome . "editado com sucesso!";
        } elseif ($_GET['sucesso'] == 3) {
            echo $nome . "excluído com sucesso!";
        }?>
    </p>
<?php endif; ?>

<?php if (isset($_GET['erro'])): ?>
    <p style="transition: opacity 1s ease; margin-top: 20px;" class="alerta-erro sumir"> 
        <?php if ($_GET['erro'] == 1) {
            echo "erro ao editar".$nome;
        } elseif ($_GET['erro'] == 2) {
            echo "erro ao excluir".$nome;
        }?>
       </p>
<?php endif; ?>