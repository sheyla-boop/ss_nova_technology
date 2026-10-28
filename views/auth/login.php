<section class="auth-panel">
    <p class="eyebrow">Cuenta SS Nova</p>
    <h1>Iniciar sesión</h1>
    <?php if (!empty($error)): ?><p class="form-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <form method="post" action="/SS_NOVA_TECHNOLOGY/public/login">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
        <label for="role">Tipo de cuenta</label>
        <select id="role" name="role" required>
            <option value="customer" <?= ($role ?? 'customer') === 'customer' ? 'selected' : '' ?>>Cliente</option>
            <option value="admin" <?= ($role ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
        </select>
        <label for="email">Correo electrónico</label>
        <input id="email" name="email" type="email" value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>" required autocomplete="email">
        <label for="password">Contraseña</label>
        <input id="password" name="password" type="password" required autocomplete="current-password">
        <button class="button" type="submit">Entrar</button>
    </form>
    <p class="form-note"><a href="/SS_NOVA_TECHNOLOGY/public/password-policy">Consulta la política de contraseñas</a>.</p>
    <p class="form-note">¿Aún no tienes cuenta? <a href="/SS_NOVA_TECHNOLOGY/public/register">Regístrate</a></p>
</section>
