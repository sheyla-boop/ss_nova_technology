# SS Nova Technology
## Fase 4: Autenticacion y seguridad

**Version:** 1.0  
**Fecha:** 2026-08-21  
**Estado:** Completada

## Funcionalidades implementadas

- Registro de clientes con validacion de nombre, correo y contrasena.
- Inicio de sesion con `password_verify`.
- Cierre de sesion mediante POST protegido por CSRF.
- Regeneracion del identificador de sesion al iniciar sesion.
- Cookies de sesion `HttpOnly`, `SameSite=Lax` y `Secure` cuando se usa HTTPS.
- Token CSRF generado con `random_bytes` y validado con `hash_equals`.
- Consultas PDO preparadas para buscar y crear usuarios.
- Correo normalizado a minusculas y validado en servidor.
- Mensajes de error escapados antes de mostrarse en HTML.
- Redireccion de usuarios autenticados fuera de login y registro.
- Formularios responsive para login y registro.

## Archivos principales

- `core/Auth.php`: estado de autenticacion y destruccion de sesion.
- `core/Csrf.php`: generacion y validacion de tokens.
- `models/User.php`: consultas de usuarios.
- `controllers/AuthController.php`: casos de uso de registro, login y logout.
- `views/auth/login.php`: formulario de acceso.
- `views/auth/register.php`: formulario de registro.
- `public/index.php`: inicio de sesion y registro de rutas.

## Rutas

| Metodo | Ruta | Funcion |
|---|---|---|
| GET | `/login` | Muestra el formulario de acceso. |
| POST | `/login` | Valida credenciales y crea la sesion. |
| GET | `/register` | Muestra el formulario de registro. |
| POST | `/register` | Crea una cuenta cliente. |
| POST | `/logout` | Destruye la sesion actual. |

## Pruebas realizadas

- Sintaxis de todos los archivos PHP validada con PHP de XAMPP.
- Rutas GET de login y registro comprobadas.
- Ambos formularios generan token CSRF.
- Conexion PDO a la base de datos comprobada.
- No se crearon usuarios de prueba ni se alteraron los datos existentes.

## Criterios de aceptacion

- [x] Registro de clientes.
- [x] Inicio de sesion.
- [x] Cierre de sesion.
- [x] Hash seguro de contrasenas.
- [x] Sesiones seguras.
- [x] Proteccion CSRF.
- [x] Validacion del servidor.
- [x] Consultas preparadas.
- [x] Vistas responsive.

## Siguiente fase

La Fase 5 implementara el CRUD administrativo de usuarios, roles y permisos, incluyendo middleware de autorizacion para restringir el panel a administradores.
