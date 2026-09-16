<section class="auth-panel">
    <p class="eyebrow">Cuenta SS Nova</p>
    <h1>Iniciar sesion</h1>
    <?php if (!empty($error)): ?><p class="form-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <form method="post" action="/SS_NOVA_TECHNOLOGY/public/login">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
        <label for="email">Correo electronico</label>
        <input id="email" name="email" type="email" value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>" required autocomplete="email">
        <label for="password">Contrasena</label>
        <input id="password" name="password" type="password" required autocomplete="current-password">
        <button class="button" type="submit">Entrar</button>
    </form>
    <p class="form-note">Aun no tienes cuenta? <a href="/SS_NOVA_TECHNOLOGY/public/register">Registrate</a></p>
</section>
