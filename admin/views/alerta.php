<?php if (isset($alerta["mensaje"]) and isset($alerta["tipo"])): ?>
    <div class="alert alert-<?php echo $alerta ["tipo"] ?>" role="alert">
        <?php echo $alerta ["mensaje"]; ?>
    </div>
<?php endif; ?>