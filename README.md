# ss_nova_technology

## Actualizar una base de datos existente

Si la base de datos ya fue creada antes de agregar la aceptacion de la politica de datos, ejecuta una sola vez en phpMyAdmin el script `database/migrations/20261016_add_privacy_consent.sql`. Para instalaciones nuevas, usa el esquema actualizado de `database/schema.sql`.

Las cuentas ya existentes conservaran los campos de aceptacion vacios; la nueva aceptacion se registra al crear cada cuenta nueva.