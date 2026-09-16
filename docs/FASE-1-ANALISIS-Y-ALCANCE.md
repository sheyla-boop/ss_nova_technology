# SS Nova Technology
## Fase 1: Analisis y alcance del sistema

**Version:** 1.0  
**Fecha:** 2026-08-21  
**Estado:** Aprobado como base para la Fase 2

## 1. Descripcion del proyecto

SS Nova Technology sera una plataforma web de comercio electronico para vender productos tecnologicos para el hogar, dispositivos moviles, computadores, accesorios y categorias relacionadas.

La plataforma tendra dos experiencias principales:

- **Tienda publica:** catalogo, busqueda, filtros, detalle de productos y compra.
- **Panel administrativo:** gestion de catalogo, inventario, usuarios y pedidos.

El sistema priorizara una navegacion sencilla, datos protegidos, control de inventario y seguimiento del pedido hasta su entrega.

## 2. Objetivo general

Desarrollar una tienda virtual confiable y responsive que permita a los clientes encontrar productos tecnologicos, crear pedidos y elegir pago contraentrega, mientras el personal administrativo controla el catalogo, las existencias y el despacho.

## 3. Objetivos especificos

1. Organizar los productos por categorias y marcas.
2. Facilitar la busqueda y comparacion basica mediante filtros.
3. Proteger las cuentas y los datos personales de los usuarios.
4. Permitir la gestion completa del carrito y el proceso de compra.
5. Mantener existencias coherentes al crear pedidos.
6. Permitir consultar y actualizar el estado de cada pedido.
7. Proporcionar operaciones administrativas con permisos segun el rol.

## 4. Alcance de la primera version

### Incluido

- Registro, inicio y cierre de sesion.
- Roles de cliente y administrador.
- Perfil del cliente y direcciones de entrega.
- Catalogo publico de productos.
- Busqueda, filtrado, ordenamiento y paginacion.
- Categorias, marcas, productos e imagenes.
- Inventario y validacion de existencias.
- Carrito persistente para usuarios autenticados.
- Creacion de pedidos.
- Pago contraentrega.
- Gestion del estado del pedido.
- Historial de pedidos del cliente.
- Panel administrativo.
- CRUD de las entidades que lo requieran.
- Validacion del servidor y proteccion contra SQL Injection, XSS y CSRF.

### Fuera de alcance inicial

- Cobros con tarjeta o integracion real con una pasarela de pagos.
- Aplicacion movil nativa.
- Integracion automatica con empresas de mensajeria.
- Recomendaciones basadas en inteligencia artificial.
- Multiidioma y multimoneda.
- Facturacion electronica legal.
- Chat en tiempo real.

Estas funciones podran planificarse como una segunda version sin alterar el nucleo del sistema.

## 5. Actores y permisos principales

| Actor | Responsabilidades |
|---|---|
| Visitante | Consultar el catalogo, buscar productos y ver detalles. |
| Cliente | Gestionar su cuenta, direcciones, carrito, pedidos e historial. |
| Administrador | Gestionar usuarios, roles, categorias, marcas, productos, inventario y pedidos. |
| Sistema | Validar datos, controlar sesiones, calcular totales, reservar existencias y registrar cambios de estado. |

### Matriz inicial de permisos

| Funcion | Visitante | Cliente | Administrador |
|---|---:|---:|---:|
| Ver catalogo | Si | Si | Si |
| Buscar y filtrar productos | Si | Si | Si |
| Gestionar cuenta propia | No | Si | Si |
| Gestionar carrito | No | Si | No aplica |
| Crear pedido | No | Si | No aplica |
| Ver pedidos propios | No | Si | Si |
| Gestionar usuarios | No | No | Si |
| Gestionar catalogo | No | No | Si |
| Gestionar inventario | No | No | Si |
| Actualizar estado de pedidos | No | No | Si |

## 6. Casos de uso

### CU-01: Consultar productos

- **Actor:** Visitante, cliente o administrador.
- **Flujo principal:** El actor abre el catalogo, aplica filtros opcionales y consulta el detalle de un producto.
- **Resultado:** Se muestran solamente productos activos y su disponibilidad actual.

### CU-02: Registrarse

- **Actor:** Visitante.
- **Flujo principal:** Completa nombre, correo y contrasena; el sistema valida los datos, cifra la contrasena y crea la cuenta con rol cliente.
- **Resultado:** La cuenta queda disponible para iniciar sesion.

### CU-03: Iniciar sesion

- **Actor:** Cliente o administrador.
- **Flujo principal:** Introduce sus credenciales; el sistema verifica la contrasena y el estado de la cuenta.
- **Resultado:** Se crea una sesion y se habilitan las rutas autorizadas para su rol.

### CU-04: Gestionar carrito

- **Actor:** Cliente.
- **Flujo principal:** Agrega productos, modifica cantidades o elimina items.
- **Resultado:** El sistema recalcula subtotales y valida que las cantidades no superen el inventario.

### CU-05: Crear pedido

- **Actor:** Cliente.
- **Flujo principal:** Selecciona una direccion, confirma productos, elige pago contraentrega y confirma la compra.
- **Resultado:** Se crea el pedido con sus detalles, total y estado inicial; el inventario se actualiza en una transaccion.

### CU-06: Gestionar catalogo

- **Actor:** Administrador.
- **Flujo principal:** Crea, consulta, modifica, activa o desactiva categorias, marcas y productos.
- **Resultado:** El catalogo publico refleja unicamente informacion valida y activa.

### CU-07: Gestionar pedido

- **Actor:** Administrador.
- **Flujo principal:** Consulta pedidos, revisa el detalle y cambia su estado segun el flujo logistico.
- **Resultado:** El cliente puede consultar el nuevo estado y queda un historial del cambio.

## 7. Flujo principal de compra

1. El visitante consulta el catalogo.
2. Abre el detalle de un producto.
3. Se registra o inicia sesion.
4. Agrega productos al carrito.
5. Revisa cantidades y disponibilidad.
6. Selecciona o registra una direccion.
7. Selecciona pago contraentrega.
8. Confirma el pedido.
9. El sistema calcula el total y descuenta el inventario dentro de una transaccion.
10. El pedido queda en estado `pendiente`.
11. El administrador confirma, prepara y despacha el pedido.
12. El pedido termina en `entregado`, `cancelado` o en otro estado definido.

## 8. Reglas de negocio

1. Cada correo electronico debe ser unico.
2. Las contrasenas nunca se almacenan en texto plano.
3. Una cuenta inactiva no puede iniciar sesion.
4. Un cliente solo puede consultar y modificar sus propios datos y pedidos.
5. Solo un administrador puede acceder al panel administrativo.
6. Un producto debe pertenecer a una categoria y puede asociarse con una marca.
7. El precio de venta debe ser mayor o igual a cero; el precio no puede ser nulo.
8. El inventario disponible nunca puede ser negativo.
9. No se puede agregar al carrito una cantidad menor que uno.
10. No se puede confirmar un pedido si el inventario disponible es insuficiente.
11. El precio y el nombre guardados en el detalle del pedido deben conservar el valor aplicado al momento de la compra.
12. El total del pedido debe calcularse en el servidor, nunca confiar solamente en el navegador.
13. Un pedido confirmado debe conservar su historial de estados.
14. Un producto con pedidos historicos no debe eliminarse fisicamente; debe desactivarse.
15. Una categoria o marca relacionada con productos activos no debe eliminarse sin resolver primero la relacion.
16. La cancelacion de un pedido debe devolver las existencias solamente cuando el pedido ya las haya descontado.
17. El pago contraentrega no debe marcarse como pagado hasta que exista confirmacion de entrega y cobro.
18. Todas las acciones administrativas relevantes deben validar autenticacion y autorizacion en el servidor.

## 9. Requisitos funcionales

| ID | Requisito |
|---|---|
| RF-01 | El sistema debe permitir registrar clientes con datos validos. |
| RF-02 | El sistema debe autenticar clientes y administradores. |
| RF-03 | El sistema debe permitir recuperar y actualizar datos del perfil. |
| RF-04 | El sistema debe mostrar productos activos con precio y disponibilidad. |
| RF-05 | El sistema debe permitir buscar, filtrar, ordenar y paginar productos. |
| RF-06 | El administrador debe gestionar categorias, marcas y productos. |
| RF-07 | El administrador debe consultar y ajustar el inventario. |
| RF-08 | El cliente debe gestionar su carrito. |
| RF-09 | El cliente debe crear pedidos usando una direccion y un metodo de pago. |
| RF-10 | El sistema debe validar inventario y crear pedidos de forma transaccional. |
| RF-11 | El cliente debe consultar sus pedidos y sus estados. |
| RF-12 | El administrador debe actualizar estados y consultar el historial. |
| RF-13 | El sistema debe validar los datos y mostrar mensajes comprensibles. |
| RF-14 | El sistema debe restringir las funciones segun autenticacion y rol. |

## 10. Requisitos no funcionales

- **Seguridad:** PDO con consultas preparadas, contrasenas con `password_hash`, tokens CSRF, escape de salida HTML, sesiones seguras y validacion de archivos.
- **Mantenibilidad:** arquitectura MVC, responsabilidades separadas, nombres descriptivos y configuracion centralizada.
- **Compatibilidad:** PHP 8.2+, MySQL 8+, Apache con mod_rewrite y navegadores modernos.
- **Usabilidad:** interfaz responsive, navegacion consistente, formularios con errores claros y estados visibles.
- **Integridad:** operaciones de pedido e inventario ejecutadas con transacciones.
- **Rendimiento:** indices en correos, claves foraneas, estados, nombres de producto y campos de filtrado.
- **Disponibilidad:** manejo de errores sin exponer trazas, credenciales ni informacion interna al usuario.
- **Escalabilidad:** modelos y servicios preparados para agregar otros metodos de pago y estados logisticos.

## 11. Decisiones tecnicas iniciales

- Se utilizara PHP sin framework para establecer claramente la arquitectura MVC.
- PDO sera la unica capa de acceso a MySQL.
- El proyecto usara un Front Controller para centralizar las solicitudes.
- Las eliminaciones de productos, usuarios y pedidos seran preferentemente logicas cuando exista informacion relacionada.
- El carrito se asociara al usuario autenticado; la incorporacion de carrito para visitantes queda como mejora posterior.
- La primera version implementara pago contraentrega como metodo registrado, sin procesar pagos en linea.
- La configuracion sensible no se almacenara en el repositorio.

## 12. Criterios de aceptacion de la Fase 1

- [x] El objetivo y el alcance de la primera version estan definidos.
- [x] Los actores y permisos iniciales estan identificados.
- [x] El flujo principal de compra esta documentado.
- [x] Las reglas de negocio principales estan documentadas.
- [x] Los requisitos funcionales y no funcionales estan enumerados.
- [x] Las decisiones tecnicas que afectan la base de datos estan registradas.
- [ ] El modelo entidad-relacion y el script SQL seran entregados en la Fase 2.
- [ ] La estructura MVC sera entregada en la Fase 3.

## 13. Preguntas pendientes para fases posteriores

1. Cual sera la ciudad o zona inicial de cobertura de domicilios?
2. El costo de envio sera fijo, por zona o calculado por distancia?
3. Se requerira identificacion tributaria o factura desde la primera version?
4. Que informacion adicional debe solicitarse al cliente para la entrega?
5. Cuales estados logisticos exactos utilizara el negocio?

Las preguntas anteriores no bloquean la Fase 2; se usaran valores configurables o supuestos documentados mientras se obtiene la respuesta del negocio.

## 14. Siguiente fase

La Fase 2 debe transformar estos requisitos en un modelo entidad-relacion, definir las tablas y relaciones, y generar el script SQL inicial con roles, estados y un usuario administrador de prueba.
