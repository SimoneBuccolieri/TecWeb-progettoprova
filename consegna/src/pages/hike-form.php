<section class="form-panel editor">
    <h1><?= e($title) ?></h1>
    <p>Tutti i campi sono obbligatori. I dati saranno visibili nel catalogo.</p>
    <?php error_summary($errors); ?>
    <form method="post" action="gestisci-escursione.php<?= $id ? '?id='.(int)$id : '' ?>">
        <?= csrf() ?>
        <label for="title">Nome dell’escursione</label>
        <input id="title" name="title" value="<?= e($data['title']) ?>" minlength="3" maxlength="100" required="required"<?= field_error($errors,'title') ?> />
        <?php error_text($errors,'title'); ?>
        <label for="location">Località</label>
        <input id="location" name="location" value="<?= e($data['location']) ?>" minlength="2" maxlength="100" required="required"<?= field_error($errors,'location') ?> />
        <?php error_text($errors,'location'); ?>
        <div class="form-grid">
            <div>
                <label for="difficulty">Difficoltà</label>
                <select id="difficulty" name="difficulty" required="required"<?= field_error($errors,'difficulty') ?>>
                    <?= difficulty_options($data['difficulty'],false) ?>
                </select>
                <?php error_text($errors,'difficulty'); ?>
            </div>
            <div>
                <label for="distance">Lunghezza (km)</label>
                <input id="distance" name="distance" type="number" min="0.1" max="100" step="0.1" value="<?= e($data['distance']) ?>" required="required"<?= field_error($errors,'distance') ?> />
                <?php error_text($errors,'distance'); ?>
            </div>
            <div>
                <label for="duration">Durata (minuti)</label>
                <input id="duration" name="duration" type="number" min="1" max="1440" step="1" value="<?= e($data['duration']) ?>" required="required"<?= field_error($errors,'duration') ?> />
                <?php error_text($errors,'duration'); ?>
            </div>
            <div>
                <label for="elevation">Dislivello positivo (metri)</label>
                <input id="elevation" name="elevation" type="number" min="0" max="5000" step="1" value="<?= e($data['elevation']) ?>" required="required"<?= field_error($errors,'elevation') ?> />
                <?php error_text($errors,'elevation'); ?>
            </div>
        </div>
        <label for="description">Descrizione</label>
        <p class="hint" id="description-hint">Da 30 a 5000 caratteri. Separa i paragrafi con una riga vuota.</p>
        <textarea id="description" name="description" rows="9" minlength="30" maxlength="5000" required="required"<?= field_error($errors,'description','description-hint') ?>><?= e($data['description']) ?></textarea>
        <?php error_text($errors,'description'); ?>
        <label for="image">Immagine illustrativa</label>
        <select id="image" name="image" required="required"<?= field_error($errors,'image') ?>>
            <option value="">Scegli un’immagine</option>
            <?php foreach (['montagne.jpg'=>'Valle e montagne','cime.jpg'=>'Cime rocciose','prati.jpg'=>'Prati alpini'] as $file=>$label): ?>
            <option value="<?= e($file) ?>"<?= $data['image']===$file ? ' selected="selected"' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
        </select>
        <?php error_text($errors,'image'); ?>
        <div class="actions">
            <button type="submit">Salva escursione</button>
            <a href="admin.php">Annulla</a>
        </div>
    </form>
</section>
