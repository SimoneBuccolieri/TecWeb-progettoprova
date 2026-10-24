<div class="auth-layout">
    <section class="auth-aside">
        <h1>Accesso</h1>
        <p>Accedi per gestire i preferiti e le tue recensioni.</p>
    </section>
    <section class="form-panel" aria-labelledby="login-title">
        <h2 id="login-title">Accedi al tuo account</h2>
        <p>Non hai un account? <a href="registrazione.php">Registrati</a>.</p>
        <?php error_summary($errors); ?>
        <form method="post" action="login.php">
            <?= csrf() ?>
            <label for="username">Nome utente</label>
            <input id="username" name="username" value="<?= e($username) ?>" autocomplete="username" required="required" maxlength="30"<?= field_error($errors,'username') ?> />
            <?php error_text($errors,'username'); ?>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required="required" maxlength="72" />
            <button type="submit">Accedi</button>
        </form>
    </section>
</div>
