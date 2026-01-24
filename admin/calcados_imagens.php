$imagens = buscarImagensPorVariacaoId($pdo, $id);

<h3 style="margin-bottom: 40px;">Imagens</h3>
<div class="imagens-editar">
    <?php foreach ($imagens as $index => $imagem): ?>
        <div class="campo-imagem-editar">
            <div class="container-imagem-editar">
                <img class="preview-img"
                    src="../media/img/calcados/<?php echo htmlspecialchars($imagem->getCaminhoArquivo()); ?>" width="100">

                <input type="file" name="imagem<?php echo $index + 1; ?>" class="input-img"
                    id="imagem<?php echo $index + 1; ?>"
                    accept="image/png, image/jpeg, image/gif, image/svg+xml, image/webp">

                <label for="imagem<?php echo $index + 1; ?>">
                    Trocar Imagem
                </label>
            </div>
        </div>
    <?php endforeach; ?>
</div>