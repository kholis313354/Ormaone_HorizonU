<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul>
            <?php if(is_array(session()->getFlashdata('errors'))) : ?>
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li><?= esc(session()->getFlashdata('errors')) ?></li>
            <?php endif; ?>
        </ul>
    </div>
<?php endif; ?>