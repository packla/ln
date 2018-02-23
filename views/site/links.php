<?php foreach ($domains as $domain): ?>
    <span>
        <a href="<?= $domain->domain ?>" target="_blank">
            <?= $domain->getParam('gorod') ?>
        </a>
    </span>
<?php endforeach; ?>
