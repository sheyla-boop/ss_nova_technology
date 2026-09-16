# SS Nova Technology
## Fase 5: CRUD administrativo de usuarios

**Estado:** Completada

Esta fase agrega la autorizacion por rol administrador y permite listar, crear, editar, activar y desactivar usuarios desde `/admin/users`. La desactivacion es logica para conservar la integridad de pedidos y relaciones historicas.

### Rutas

| Metodo | Ruta | Funcion |
|---|---|---|
| GET | `/admin/users` | Listado de usuarios |
| GET | `/admin/users/create` | Formulario de alta |
| POST | `/admin/users/store` | Crea un usuario |
| GET | `/admin/users/edit?id=ID` | Formulario de edicion |
| POST | `/admin/users/update` | Actualiza un usuario |
| POST | `/admin/users/toggle` | Activa o desactiva un usuario |

### Pruebas de aceptacion

- Iniciar sesion con un usuario cuyo rol sea `admin`.
- Confirmar que aparece el enlace Administracion.
- Crear y editar un usuario.
- Activar y desactivar otro usuario.
- Confirmar que un cliente recibe HTTP 403 al visitar el panel.
- Confirmar que los formularios rechazan tokens CSRF invalidos.