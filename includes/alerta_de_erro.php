<?php if (!empty($erros)): ?>
    <div class="alerta-erro" style="margin-top: 20px;">
        <ul style="padding-left: 20px; margin: 0;">
            <strong>Erros detectados:</strong>
            <?php foreach ($erros as $erro): ?>
                <li><?php echo $erro; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>