<article class="policy-content">
    <p class="eyebrow">SS Nova Technology · Version <?= htmlspecialchars($policyVersion, ENT_QUOTES, 'UTF-8') ?></p>
    <h1>Politica de tratamiento de datos</h1>
    <p>Esta politica explica como SS Nova Technology trata los datos personales que se proporcionan al crear una cuenta, navegar por la tienda o realizar una compra. Debe revisarse y adecuarse a las leyes aplicables y a la operacion real del negocio antes de su publicacion definitiva.</p>

    <h2>Datos que podemos tratar</h2>
    <ul>
        <li>Datos de cuenta y contacto, como nombre, apellido, correo electronico y, cuando se proporcione, telefono.</li>
        <li>Informacion que se facilite para gestionar compras, pedidos, entregas, consultas y soporte.</li>
        <li>Datos tecnicos necesarios para mantener la sesion, proteger el sitio y diagnosticar errores.</li>
    </ul>
    <p>Las contraseñas se almacenan como hashes mediante las funciones seguras de PHP; no se guardan como texto legible.</p>

    <h2>Finalidades</h2>
    <p>Usamos los datos para crear y administrar cuentas, autenticar usuarios, procesar pedidos, atender solicitudes, prestar soporte, proteger el servicio y cumplir obligaciones legales aplicables. No se deben usar para finalidades incompatibles con estas sin informar previamente y obtener la autorizacion que corresponda.</p>

    <h2>Conservacion y seguridad</h2>
    <p>Conservamos la informacion durante el tiempo necesario para prestar el servicio y atender obligaciones legales, contables o de seguridad. Aplicamos controles de acceso y medidas tecnicas razonables; ningun sistema puede garantizar seguridad absoluta.</p>
    <p>La aceptacion se registra con la version de esta politica y la fecha y hora en que se creo la cuenta. Los datos de cuentas antiguas pueden no tener ese registro.</p>

    <h2>Comparticion de datos</h2>
    <p>No vendemos datos personales. Solo se comparten cuando sea necesario para operar el servicio (por ejemplo, alojamiento o gestion de pedidos), cuando exista autorizacion o cuando lo exija la ley. Los proveedores deben recibir unicamente la informacion necesaria para su funcion.</p>

    <h2>Derechos y solicitudes</h2>
    <p>Puedes solicitar acceso, correccion o eliminacion de tus datos, retirar una autorizacion cuando sea legalmente posible o plantear una consulta sobre su tratamiento. La solicitud se atendera segun la normativa aplicable y las obligaciones de conservacion vigentes.</p>
    <?php if (!empty($support['email'])): ?>
        <p>Contacta a <a href="mailto:<?= htmlspecialchars($support['email'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($support['email'], ENT_QUOTES, 'UTF-8') ?></a> para consultas o solicitudes relacionadas con tus datos.</p>
    <?php else: ?>
        <p>Antes de publicar el sitio, configura un correo de contacto en SUPPORT_EMAIL para recibir solicitudes sobre datos personales.</p>
    <?php endif; ?>

    <h2>Actualizaciones</h2>
    <p>Esta politica puede actualizarse para reflejar cambios en el servicio o en las normas aplicables. La version vigente se identifica al inicio de esta pagina.</p>
    <p class="form-note">Esta politica es una base informativa y no sustituye la revision legal correspondiente ni la identificacion del responsable y la jurisdiccion aplicable.</p>
</article>
