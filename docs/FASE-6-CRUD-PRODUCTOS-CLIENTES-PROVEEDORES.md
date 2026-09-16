# SS Nova Technology
## Fase 6: CRUD de productos, clientes y proveedores

### Implementado

- **Productos:** listado, creación, edición, precio, SKU, slug, categoría, descripción, stock y estado.
- **Clientes:** listado filtrado por el rol `customer`; creación, edición, activación y desactivación mediante el CRUD de usuarios.
- **Proveedores:** listado, creación, edición, activación y desactivación lógica.
- Todas las rutas administrativas exigen sesión con rol `admin` y usan protección CSRF en formularios POST.

### Rutas

| Metodo | Ruta | Funcion |
|---|---|---|
| GET | `/admin/clients` | Listado de clientes |
| GET | `/admin/products` | Listado de productos |
| GET | `/admin/products/form` | Crear o editar producto; usa `?id=ID` para editar |
| POST | `/admin/products/save` | Guarda un producto |
| GET | `/admin/suppliers` | Listado de proveedores |
| GET | `/admin/suppliers/form` | Crear o editar proveedor; usa `?id=ID` para editar |
| POST | `/admin/suppliers/save` | Guarda un proveedor |
| POST | `/admin/suppliers/toggle` | Activa o desactiva un proveedor |

### Base de datos

La tabla `suppliers` fue añadida a `database/schema.sql`. Si la base ya estaba creada, ejecuta únicamente el bloque `CREATE TABLE suppliers` en phpMyAdmin antes de usar el módulo de proveedores. No vuelvas a ejecutar el esquema completo sobre una base con datos, porque el script inicial elimina las tablas existentes.

### Verificacion

Todos los archivos PHP del proyecto pasaron la validacion de sintaxis con `C:\xampp\php\php.exe -l`.