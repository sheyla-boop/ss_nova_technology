<section class="auth-panel">
    <p class="eyebrow">Cuenta SS Nova</p>
    <h1>Crear cuenta</h1>
    <?php if (!empty($errors)): ?>
        <div class="form-error" role="alert">
            <?php foreach ($errors as $formError): ?><p><?= htmlspecialchars($formError, ENT_QUOTES, 'UTF-8') ?></p><?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="post" action="/SS_NOVA_TECHNOLOGY/public/register">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
        <label for="first_name">Nombre</label>
        <input id="first_name" name="first_name" value="<?= htmlspecialchars($firstName ?? '', ENT_QUOTES, 'UTF-8') ?>" required autocomplete="given-name">
        <label for="last_name">Apellido</label>
        <input id="last_name" name="last_name" value="<?= htmlspecialchars($lastName ?? '', ENT_QUOTES, 'UTF-8') ?>" required autocomplete="family-name">
        <label for="register_email">Correo electrónico</label>
        <input id="register_email" name="email" type="email" value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>" required autocomplete="email">
        <label for="register_role">Tipo de cuenta</label>
        <select id="register_role" name="role" required>
            <option value="customer" <?= ($role ?? 'customer') === 'customer' ? 'selected' : '' ?>>Cliente</option>
            <option value="admin" <?= ($role ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
        </select>
        <p class="form-note">Las cuentas de administrador tienen acceso a las funciones administrativas del sistema.</p>
        <label for="register_password">Contraseña</label>
        <input id="register_password" name="password" type="password" minlength="8" maxlength="200" pattern="(?=.*[\p{P}\p{S}]).*" title="Incluye al menos un signo o símbolo." required autocomplete="new-password" aria-describedby="password-help">
        <p id="password-help" class="form-note">Usa al menos 8 caracteres y un signo. Evita contraseñas reutilizadas o fáciles de adivinar. <a href="/SS_NOVA_TECHNOLOGY/public/password-policy">Ver política completa</a>.</p>
        <label class="consent-label">
            <input type="checkbox" name="privacy_consent" value="1" required <?= !empty($privacyAccepted) ? 'checked' : '' ?>>
            He leído y acepto la <a href="/SS_NOVA_TECHNOLOGY/public/privacy-policy" target="_blank" rel="noopener">política de tratamiento de datos</a>.
        </label>
        <button class="button" type="submit">Crear cuenta</button>
    </form>
    <p class="form-note">¿Ya tienes cuenta? <a href="/SS_NOVA_TECHNOLOGY/public/login">Inicia sesión</a></p>
</section>
