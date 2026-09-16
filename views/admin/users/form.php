<section class="admin-header"><div><p class="eyebrow">Administracion</p><h1><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1></div><a href="/SS_NOVA_TECHNOLOGY/public/admin/users">Volver</a></section>
<?php if (!empty($errors)): ?><div class="form-error" role="alert"><?php foreach ($errors as $error): ?><p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endforeach; ?></div><?php endif; ?>
<form class="admin-form" method="post" action="/SS_NOVA_TECHNOLOGY/public/admin/users/<?= $user ? 'update' : 'store' ?>">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
    <?php if ($user): ?><input type="hidden" name="id" value="<?= (int) $user['id'] ?>"><?php endif; ?>
    <label>Nombre<input name="first_name" required maxlength="80" value="<?= htmlspecialchars($user['first_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></label>
    <label>Apellido<input name="last_name" required maxlength="80" value="<?= htmlspecialchars($user['last_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></label>
    <label>Correo<input type="email" name="email" required maxlength="190" value="<?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></label>
    <label>Telefono<input name="phone" maxlength="30" value="<?= htmlspecialchars($user['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></label>
    <label>Rol<select name="role_id" required><?php foreach ($roles as $role): ?><option value="<?= (int) $role['id'] ?>" <?= (int) ($user['role_id'] ?? 0) === (int) $role['id'] ? 'selected' : '' ?>><?= htmlspecialchars($role['description'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select></label>
    <label>Contrasena<?= $user ? ' (dejar vacia para conservarla)' : '' ?><input type="password" name="password" <?= $user ? '' : 'required' ?> minlength="8" maxlength="255"></label>
    <button class="button" type="submit">Guardar usuario</button>
</form>