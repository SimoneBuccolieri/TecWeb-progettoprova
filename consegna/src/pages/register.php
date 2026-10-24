<div class="auth-layout">
    <section class="auth-aside">
        <h1>Registrazione</h1>
        <p>Crea un account per salvare escursioni e scrivere recensioni.</p>
    </section>
    <section class="form-panel" aria-labelledby="register-title">
        <h2 id="register-title">Crea il tuo account</h2>
        <p>Hai già un account? <a href="login.php">Accedi</a>.</p>
        <p>Tutti i campi sono obbligatori. In questo sito di prova usa dati inventati.</p>
        <?php error_summary($errors); ?>
        <form method="post" action="registrazione.php" id="register-form">
            <?= csrf() ?>
            <label for="username">Nome utente</label>
            <p class="hint" id="username-hint">Da 3 a 30 lettere, numeri o trattini bassi.</p>
            <input id="username" name="username" value="<?= e($username) ?>" pattern="[A-Za-z0-9_]{3,30}" minlength="3" maxlength="30" autocomplete="username" required="required"<?= field_error($errors,'username','username-hint') ?> />
            <?php error_text($errors,'username'); ?>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="<?= e($email) ?>" maxlength="254" autocomplete="email" required="required"<?= field_error($errors,'email') ?> />
            <?php error_text($errors,'email'); ?>
            <label for="password">Password</label>
            <p class="hint" id="password-hint">Almeno 8 caratteri. Massimo 72 byte; le lettere accentate possono occupare più byte.</p>
            <input id="password" name="password" type="password" minlength="8" maxlength="72" autocomplete="new-password" required="required"<?= field_error($errors,'password','password-hint') ?> />
            <?php error_text($errors,'password'); ?>
            <label for="confirm">Ripeti la password</label>
            <input id="confirm" name="confirm" type="password" minlength="8" maxlength="72" autocomplete="new-password" required="required"<?= field_error($errors,'confirm') ?> />
            <?php error_text($errors,'confirm'); ?>
            <button type="submit">Crea account</button>
        </form>
    </section>
</div>
