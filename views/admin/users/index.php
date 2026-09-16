<section class="admin-header">
    <div><p class="eyebrow">Administracion</p><h1><?= !empty($clients) ? 'Clientes' : 'Usuarios' ?></h1></div>
    <a class="button" href="/SS_NOVA_TECHNOLOGY/public/admin/users/create">Nuevo usuario</a>
</section>
<div class="table-wrap"><table><thead><tr><th>Nombre</th><th>Correo</th><th>Rol</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
<?php foreach ($users as $item): ?><tr>
    <td><?= htmlspecialchars($item['first_name'] . ' ' . $item['last_name'], ENT_QUOTES, 'UTF-8') ?></td>
    <td><?= htmlspecialchars($item['email'], ENT_QUOTES, 'UTF-8') ?></td>
    <td><?= htmlspecialchars($item['role_description'] ?? ($item['role_name'] ?? 'Cliente'), ENT_QUOTES, 'UTF-8') ?></td>
    <td><?= (bool) $item['is_active'] ? 'Activo' : 'Inactivo' ?></td>
    <td class="actions"><a href="/SS_NOVA_TECHNOLOGY/public/admin/users/edit?id=<?= (int) $item['id'] ?>">Editar</a><form method="post" action="/SS_NOVA_TECHNOLOGY/public/admin/users/delete" onsubmit="return confirm('¿Eliminar este usuario?');"><input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>"><input type="hidden" name="id" value="<?= (int) $item['id'] ?>"><button type="submit">Eliminar</button></form><form method="post" action="/SS_NOVA_TECHNOLOGY/public/admin/users/toggle"><input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>"><input type="hidden" name="id" value="<?= (int) $item['id'] ?>"><button type="submit"><?= (bool) $item['is_active'] ? 'Desactivar' : 'Activar' ?></button></form></td>
</tr><?php endforeach; ?></tbody></table></div>