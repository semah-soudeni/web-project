<?php if (!empty($extraJs)): ?>
    <?php foreach ($extraJs as $js): ?>
        <script src="<?= escapeText($js) ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
</body>
</html>