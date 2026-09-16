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
        <label for="register_email">Correo electronico</label>
        <input id="register_email" name="email" type="email" value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>" required autocomplete="email">
        <label for="register_password">Contrasena</label>
        <input id="register_password" name="password" type="password" minlength="8" required autocomplete="new-password">
        <button class="button" type="submit">Crear cuenta</button>
    </form>
    <p class="form-note">Ya tienes cuenta? <a href="/SS_NOVA_TECHNOLOGY/public/login">Inicia sesion</a></p>
</section>
