# Guía de estudio y defensa oral — Pansusu / El Buen Pan

Esta guía explica el proyecto tal como está escrito: qué archivos lo componen, cómo se conectan, qué hacen sus funciones y consultas, y qué límites conviene reconocer en una defensa. No hace falta memorizar cada etiqueta: lo importante es poder explicar el recorrido de los datos y la responsabilidad de cada parte.

## 1. Resumen del sistema

Es un sitio web de una panadería implementado principalmente con **PHP**, **HTML**, **CSS**, **JavaScript** y **MySQL**. Bootstrap se usa para la presentación adaptable a distintos tamaños de pantalla.

El sitio tiene dos áreas:

- **Pública:** inicio, catálogo, información del negocio, contacto y carrito.
- **Administrativa:** inicio de sesión, tablero, administración de productos y gestión de pedidos.

La aplicación usa el servidor para generar páginas PHP, una base MySQL para guardar productos, pedidos, mensajes y administradores, y sesiones PHP para mantener el carrito y la autenticación administrativa.

### Recorrido principal de una compra

1. `productos.php` pide los productos a MySQL y los presenta por categoría.
2. Al enviar «Agregar al carrito», el formulario manda datos por `POST`.
3. `carrito.php` valida los datos y guarda o actualiza el carrito en `$_SESSION['CARRITO']`.
4. `mostrar_carrito.php` muestra cantidades, subtotales y total, y pide un correo.
5. `pagar.php` vuelve a consultar los productos en la base de datos, calcula el total con precios actuales y registra el pedido y sus detalles en una transacción.
6. Si todo se guarda, se confirma la transacción y se vacía el carrito. Si hay una excepción, se revierte.
7. El administrador revisa el pedido y cambia su estado desde `admin/seccion/pedidos.php`.

## 2. Mapa de archivos y responsabilidades

### Páginas públicas

| Archivo | Responsabilidad |
|---|---|
| `index.php` | Página de inicio y presentación de productos destacados estáticos. |
| `productos.php` | Consulta y muestra productos dinámicos por categoría; incluye el buscador del navegador y los formularios para agregar productos al carrito. |
| `carrito.php` | Lógica compartida que procesa acciones de agregar y eliminar productos del carrito de sesión. No es una página visual independiente. |
| `mostrar_carrito.php` | Presenta el carrito, calcula y muestra importes, permite quitar productos y solicita el correo para continuar. |
| `pagar.php` | Valida la solicitud y guarda el pedido y sus renglones en MySQL. |
| `nosotros.php` | Presentación institucional del negocio; su contenido es principalmente HTML fijo. |
| `contacto.php` | Formulario y datos de contacto; incluye `send.php` para procesar el envío. |
| `send.php` | Comprueba los campos y guarda el mensaje en la tabla `mensajes`. |

### Administración

| Archivo | Responsabilidad |
|---|---|
| `admin/login.php` | Verifica las credenciales del administrador y crea la sesión autenticada. |
| `admin/index.php` | Tablero con conteos, avisos de pedidos pendientes y una vista de productos recientes. |
| `admin/seccion/productos.php` | Operaciones de alta, consulta, edición y eliminación de productos, incluida la subida de imágenes. |
| `admin/seccion/pedidos.php` | Lista pedidos y detalles, y permite cambiar el estado. **En el archivo actual no se ve una comprobación de sesión administrativa**, a diferencia del tablero y la sección de productos. |
| `admin/cerrar.php` | Borra la sesión y la cookie de sesión para cerrar el acceso administrativo. |
| `admin/config/bd.php` | Configura la conexión PDO, la base de datos y constantes usadas en el carrito. |
| `admin/template/cabecera.php` | **En el código actual no es una cabecera:** vacía y destruye la sesión, redirige a `login.php` y termina la ejecución. Su nombre no coincide con lo que hace. |
| `admin/template/pie.php` | Fragmento de pie del panel; cierra `main`/HTML y carga Bootstrap, pero no tiene una cabecera administrativa funcional que abra esa estructura. |

### Plantillas, estilos, imágenes y datos

| Archivo o carpeta | Uso |
|---|---|
| `template/cabecera.php`, `template/pie.php` | Estructura común de las páginas públicas: metadatos, navegación, contenedor, pie y scripts. |
| `template_carrito/cabecera.php`, `template_carrito/pie.php` | Estructura común para el carrito y el proceso de compra; inicia la sesión si todavía no está iniciada. |
| `estilo/styles.css` | Estilos propios del proyecto, especialmente marca y panel administrativo. |
| `estilo/bootstrap.min.css` | Hoja de estilos minificada de Bootstrap; es una dependencia externa empaquetada, no código de negocio escrito para este sitio. |
| `imagen/` | Fotografías e ilustraciones usadas por las páginas y los productos. Los productos de la base almacenan el nombre del archivo, no la imagen binaria. |
| `backup/panaderia2.sql` | Volcado SQL con estructura e índices de las tablas y datos de ejemplo. Sirve para reconstruir la base. |

## 3. Conceptos básicos del código

### PHP dentro de HTML

- `<?php ... ?>` abre y cierra un bloque PHP. El servidor ejecuta ese código antes de enviar el HTML al navegador.
- `echo` imprime texto o valores en la respuesta HTML.
- `include` inserta otro archivo; `include_once` evita insertarlo más de una vez; `require` y `require_once` son equivalentes en propósito, pero si el archivo falta producen un error que detiene la ejecución.
- `__DIR__` representa la carpeta física del archivo actual. Se usa para construir rutas de inclusión que no dependan del directorio desde donde se accedió a la página.
- `$variable` es una variable PHP; por ejemplo, `$conexion`.
- `.` concatena cadenas en PHP. `+=` suma y reasigna; `.=` concatena y reasigna.
- `[]` crea arrays. Un array asociativo usa claves con nombre, como `['nombre' => $nombre]`.
- `foreach` recorre cada elemento de un array.
- `if / elseif / else` ejecuta una rama según una condición.
- `===` compara valor y tipo; `==` compara valores con conversión de tipos. Para banderas y valores conocidos, `===` suele ser más estricto.
- `!` significa «no»; `&&` significa «y»; `||` significa «o».
- `??` usa el valor de la izquierda si existe y no es `null`; si no, usa el valor de la derecha. Ejemplo: `$_POST['usuario'] ?? ''`.
- El operador ternario `condición ? valor_si_verdadero : valor_si_falso` es una forma breve de un `if/else`.
- `break` sale de un `switch` o un bucle. `exit` detiene el script, por ejemplo después de redirigir.

### Petición, formulario y sesión

- `$_GET` contiene valores enviados en la dirección URL, por ejemplo `?editar=12`.
- `$_POST` contiene valores enviados por un formulario con `method="post"`.
- `$_FILES` contiene metadatos y ruta temporal de archivos subidos.
- `$_SERVER['REQUEST_METHOD']` permite distinguir métodos HTTP como `GET` y `POST`.
- `$_SESSION` guarda datos asociados a una sesión del navegador, del lado del servidor. Se debe llamar a `session_start()` antes de leer o escribir la sesión.
- `isset($x)` pregunta si una variable o clave está definida y no es `null`. En este proyecto se usa para saber si llegó una acción, si existe el carrito o si se recibió una imagen.
- `empty($x)` detecta valores ausentes o considerados vacíos, como `''`, `0`, `null` o un array vacío. No es idéntico a `isset`: por ejemplo, una variable definida como cadena vacía está `isset`, pero está `empty`.
- `filter_input`, `filter_var`, `trim`, `strlen`, `is_numeric` e `is_string` se usan para validar o normalizar entrada. Validar formato no reemplaza validar autorización ni existencia en la base.

### Seguridad al mostrar datos y procesar formularios

- `htmlspecialchars($texto, ENT_QUOTES, 'UTF-8')` convierte caracteres especiales para que un valor se muestre como texto HTML y no se interprete como etiquetas o atributos. Es una defensa importante contra inyección de HTML/JavaScript al imprimir datos.
- Una consulta preparada separa el texto SQL de los valores que llegan del usuario. `prepare()` crea la consulta con marcadores y `execute([...])` pasa los parámetros. Esto reduce el riesgo de inyección SQL.
- Los datos recibidos del navegador no son confiables, aunque provengan de un formulario propio. Deben validarse en el servidor.
- `header('Location: ...')` envía una redirección HTTP; `exit` evita que el script continúe generando la respuesta posterior.

## 4. Conexión a la base de datos y PDO

En `admin/config/bd.php` se define el servidor local, el nombre de la base y los datos de conexión. PDO crea la conexión usando una cadena DSN que identifica el motor MySQL, host, base y juego de caracteres `utf8mb4`.

Las opciones configuradas significan:

- `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`: los errores de base de datos lanzan excepciones, que se pueden tratar con `try/catch`.
- `PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC`: al recuperar registros se reciben arrays asociativos, por ejemplo `$fila['nombre']`.
- `PDO::ATTR_EMULATE_PREPARES => false`: se prefieren consultas preparadas nativas de la base de datos.

Si falla la conexión, el `catch (PDOException $error)` detiene la página con un mensaje general, en vez de exponer detalles internos de configuración al visitante.

El archivo también define `KEY` y `COD`, que `carrito.php` y `productos.php` usan con `openssl_encrypt`/`openssl_decrypt` para transformar los valores ocultos enviados por los formularios. **No debe explicarse como una protección criptográfica completa ni como un reemplazo de la validación del servidor**: la clave está escrita en el código y el cifrado indicado usa modo ECB, que no autentica los datos.

## 5. Recorrido de las páginas públicas

### `index.php`

Incluye la cabecera común, presenta el título, una imagen, un enlace al catálogo y tres tarjetas destacadas. Esos destacados están escritos directamente en HTML, por lo que no se actualizan automáticamente al modificar la tabla `productos`. Finalmente incluye el pie común.

Clases como `container`, `row`, `col-md-6`, `card`, `shadow` y `img-fluid` son clases visuales de Bootstrap: organizan el diseño y ayudan a adaptarlo a móviles y pantallas grandes.

### `productos.php`: listado, categorías y buscador

1. Incluye `carrito.php`, que inicia la sesión y procesa una acción si el formulario fue enviado. Después incluye la cabecera.
2. Ejecuta `SELECT DISTINCT categoria FROM productos`. `SELECT` lee datos; `DISTINCT` elimina categorías repetidas del resultado.
3. `query()` ejecuta esta consulta fija, sin valores proporcionados por el visitante.
4. `while ($categoria = ...->fetch(PDO::FETCH_ASSOC))` recupera una categoría por iteración hasta que no quedan filas.
5. Por cada categoría prepara `SELECT id, nombre, precio, categoria, imagen FROM productos WHERE categoria = ?`. `WHERE` filtra filas; `?` es un marcador para un parámetro.
6. `execute([$categoriaActual])` sustituye el marcador de forma segura. `fetchAll(PDO::FETCH_ASSOC)` recupera todos los productos de esa categoría como arrays asociativos.
7. `foreach` crea una tarjeta por producto. `htmlspecialchars` protege los textos que se escriben en HTML; `number_format` da formato legible al precio.
8. Cada formulario manda `id`, `nombre`, `cantidad` y `precio` en campos ocultos. El servidor los procesa desde `carrito.php`; `type="hidden"` solo los oculta visualmente, no impide que una persona los altere.
9. El JavaScript escucha el evento `input` del buscador. Convierte a minúsculas y quita espacios laterales con `toLowerCase()` y `trim()`, busca coincidencias con `includes()`, y oculta o muestra tarjetas modificando `style.display`. El filtro ocurre en el navegador; no vuelve a consultar MySQL.
10. Si no queda ninguna tarjeta visible muestra «No se encontraron productos» y oculta títulos de categorías que quedaron sin elementos.

### `carrito.php`: acciones de agregar y eliminar

Este archivo inicia la sesión, incluye la conexión y prepara `$mensaje`.

- `isset($_POST['btnAccion'])` comprueba si el formulario envió el botón de acción.
- `switch ($_POST['btnAccion'])` separa las acciones `Agregar` y `Eliminar`.
- Para agregar, descifra cada campo y comprueba el tipo esperado. `is_numeric` acepta valores numéricos; `is_string` verifica que el nombre descifrado sea una cadena.
- Si no hay `$_SESSION['CARRITO']`, arma un array asociativo con `ID`, `NOMBRE`, `CANTIDAD` y `PRECIO`, y lo guarda en la primera posición.
- Si ya hay carrito, `array_column(..., "ID")` extrae los identificadores y `in_array` verifica si el producto ya está. Si existe, aumenta su cantidad; si no, lo agrega en la siguiente posición calculada con `count`.
- Al eliminar, vuelve a validar el identificador, recorre el carrito, quita el elemento con `unset` y usa `array_values` para reordenar los índices numéricos.
- `break` termina la acción actual del `switch`.

La sesión permite que el carrito sobreviva entre solicitudes sin guardarlo en la base de datos antes de crear el pedido. La sesión es temporal y asociada al navegador; los pedidos confirmados sí se guardan en MySQL.

**Detalle importante:** `carrito.php` verifica que los campos descifrados tengan tipos razonables, pero el precio y nombre que se guardan temporalmente vienen del formulario. El paso final `pagar.php` vuelve a consultar la base de datos y calcula el pedido con el precio guardado allí. Esa revalidación evita que el total persistido dependa del precio enviado por el navegador. Aun así, lo más sólido es que el servidor guarde solo el ID y la cantidad en el carrito y obtenga nombre y precio de la base cada vez que los necesite.

### `mostrar_carrito.php`

Incluye la conexión, la lógica del carrito y la cabecera especial. Si `$_SESSION['CARRITO']` no está vacío, recorre sus productos:

- Muestra el nombre con `htmlspecialchars`.
- Calcula el subtotal de línea como `PRECIO * CANTIDAD`.
- Acumula el total con `$total += ...`.
- `number_format(valor, 2, ',', '.')` presenta dos decimales, coma decimal y punto de miles.
- El formulario de quitar manda el ID cifrado y la acción `Eliminar`.
- El formulario de compra pide un email (`type="email"` y `required`) y envía a `pagar.php`.

La validación HTML mejora la experiencia, pero no es suficiente para confiar en el correo; el servidor lo valida de nuevo.

### `pagar.php`: registro consistente del pedido

1. `session_start()` recupera el carrito; se incluye la conexión.
2. Solo acepta `POST`. Si la solicitud no es `POST`, redirige al carrito y termina.
3. `filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)` valida el formato del email. Si falla, termina con un mensaje.
4. Comprueba que el carrito no esté vacío.
5. Inicia variables para total, detalles, ID y error.
6. Dentro de `try`, ejecuta `beginTransaction()`: las operaciones quedan dentro de una transacción.
7. Prepara `SELECT id, nombre, precio FROM productos WHERE id = ?`.
8. Por cada producto, convierte el ID a entero y valida la cantidad con `FILTER_VALIDATE_INT`. Rechaza ID no positivo o cantidad inválida/menor a uno.
9. Ejecuta la consulta usando el ID y obtiene el registro con `fetch()`. Si el producto ya no existe, lanza `Exception`.
10. Calcula el subtotal a partir del precio vigente de la base de datos y la cantidad validada. Arma `$detalles` con una copia del nombre y precio al momento de la compra.
11. Inserta una fila en `pedidos` con email, total y estado inicial `Pendiente`. `lastInsertId()` obtiene el ID generado.
12. Prepara una inserción en `detalle_pedido` y ejecuta una vez por producto.
13. Si todo sale bien, `commit()` confirma todas las inserciones y `unset($_SESSION['CARRITO'])` vacía el carrito.
14. Si hay una excepción, `rollBack()` revierte la transacción si todavía está activa, y prepara un mensaje general.
15. La vista presenta confirmación o error. `htmlspecialchars` protege los datos de salida y `number_format` formatea el total.

**¿Por qué una transacción?** Un pedido y sus detalles son una sola operación lógica. Si se inserta el pedido, pero falla un detalle, `rollBack()` evita dejar una compra incompleta. `commit()` hace permanentes todos los cambios al finalizar correctamente.

### `contacto.php` y `send.php`

`contacto.php` presenta el formulario y ejecuta `include("send.php")` antes del formulario. Cuando llega una petición con el botón `send`, `send.php`:

- Comprueba `isset($_POST['send'])`.
- Revisa que nombre, email y mensaje tengan al menos un carácter con `strlen`.
- Carga la conexión usando `require_once __DIR__ . '/admin/config/bd.php'`.
- Aplica `trim()` para quitar espacios al principio y al final.
- Prepara `INSERT INTO mensajes (nombre, email, mensaje) VALUES (?, ?, ?)` y ejecuta con los tres valores.
- Muestra un aviso de éxito o de campos incompletos.

La consulta preparada protege los valores contra inyección SQL. En el formulario, el email y mensaje son obligatorios en HTML, pero nombre no tiene `required`; en cambio, el servidor exige nombre no vacío. Es una diferencia de validación que conviene conocer.

**Alcance actual:** el código guarda el mensaje en la base; no se observa envío de correo electrónico. Tampoco se ve un manejo específico de errores de base de datos en `send.php`.

### `nosotros.php` y plantillas públicas

`nosotros.php` contiene contenido institucional fijo e incluye cabecera y pie. `template/cabecera.php` define el idioma, codificación UTF-8, viewport móvil, título, hojas de estilo, menú y apertura del elemento `<main>`. `template/pie.php` cierra `<main>`, agrega el pie y carga el JavaScript de Bootstrap.

Separar cabecera y pie evita repetir estructura y permite mantener la navegación y el diseño de varias páginas desde archivos comunes.

`template_carrito/cabecera.php` tiene un propósito similar, pero comprueba `session_status()` antes de iniciar la sesión. La comprobación evita intentar iniciar otra vez una sesión ya abierta. Su pie cierra la página de la sección de compra.

## 6. Panel administrativo

### `admin/login.php`

- Inicia la sesión.
- Calcula una ruta base a partir de `$_SERVER['SCRIPT_NAME']`, normalizando las barras. Esto permite armar rutas de redirección aunque el proyecto esté dentro de una subcarpeta.
- Si la sesión ya tiene `admin_logueado === true`, redirige al tablero.
- Procesa credenciales solo si `REQUEST_METHOD` es `POST`.
- `trim($_POST['usuario'] ?? '')` elimina espacios y usa cadena vacía si no llegó usuario; la contraseña usa `?? ''` sin recortar.
- Prepara `SELECT * FROM administradores WHERE usuario = ?` y busca el administrador.
- `password_verify($password, $administrador['password'])` compara la contraseña enviada con el hash guardado; no compara la contraseña en texto plano.
- Si coincide, `session_regenerate_id(true)` renueva el identificador para reducir el riesgo de fijación de sesión.
- Marca la sesión como autenticada, guarda el usuario y redirige. Si no coincide, muestra error.

El formulario usa `POST`, campos `required` y un campo de tipo `password`. El volcado SQL tiene una contraseña almacenada como hash, no debe confundirse con una contraseña en texto claro ni copiarse como credencial en una exposición.

### `admin/index.php`

Empieza con `session_start()` y un control de acceso: si la marca de sesión no existe o no es exactamente `true`, redirige al login y termina. Luego obtiene datos y, antes de presentar la vista, incluye `admin/template/cabecera.php`. **Ese archivo actualmente destruye la sesión y redirige al login**, por lo que la página no alcanza a renderizar el tablero tal como está. La secuencia de consultas del tablero sería:

- `SELECT * FROM productos ORDER BY id DESC` obtiene productos del más nuevo al más antiguo.
- `count($productos)` cuenta registros.
- `SELECT COUNT(*) FROM pedidos WHERE estado = ?` cuenta pedidos pendientes. `COUNT(*)` devuelve un solo número; `fetchColumn()` lee ese valor.
- El código intenta incluir la cabecera y presentar un aviso según `if ($pedidosPendientes > 0)`, pero la inclusión actual redirige y detiene la página antes de esa vista.
- Muestra los primeros diez productos con `array_slice($productos, 0, 10)`.
- `?? 'Panadería'` sirve como valor alternativo si la categoría no está disponible; `?: 'pan.png'` usa una imagen de respaldo si el nombre es vacío.

### `admin/seccion/productos.php`

Es la sección CRUD: **Create, Read, Update, Delete** (crear, leer, actualizar, eliminar).

1. Inicia sesión y comprueba que `admin_logueado` sea `true`. Sin autorización redirige al login.
2. Incluye la conexión y prepara mensajes y variables.
3. Calcula la carpeta de imágenes desde `__DIR__`; si no existe, intenta crearla con `mkdir`.
4. Si el método es `POST`, lee acción e ID. `??` evita avisos si faltan campos; `(int)` convierte el ID a entero.
5. Para agregar o guardar, recorta nombre/categoría y valida precio con `FILTER_VALIDATE_FLOAT`. Rechaza nombre o categoría vacíos y precios no válidos o no positivos.
6. Para editar, consulta primero el nombre de la imagen existente para conservarla si no se sube otra.
7. Si se recibió imagen, valida error de subida y tamaño máximo de 2 MB; detecta MIME desde el archivo temporal y permite JPEG, PNG, WEBP o GIF.
8. Genera un nombre único con `uniqid` y extensión asociada al MIME. `move_uploaded_file` mueve el archivo desde la ubicación temporal a `imagen/`.
9. Para alta usa `INSERT`; para modificación usa `UPDATE ... WHERE id = :id`. Los parámetros nombrados (`:nombre`, `:precio`, etc.) se pasan a `execute()` como array asociativo.
10. Para eliminación usa `DELETE ... WHERE id = :id`. Si la clave foránea impide borrarlo porque está asociado a un detalle de pedido, captura `PDOException` y muestra un mensaje.
11. `GET['editar']` selecciona un producto para cargarlo en el formulario. La consulta preparada evita concatenar el ID en SQL.
12. Obtiene catálogo completo con `SELECT * FROM productos ORDER BY id DESC`, muestra los resultados y usa `htmlspecialchars` al imprimirlos.
13. Después de guardar o borrar, redirige a la misma página y ejecuta `exit`. Esto evita reenviar el formulario al actualizar el navegador (patrón Post/Redirect/Get).

El formulario de imagen usa `enctype="multipart/form-data"` porque los archivos no se envían como un formulario normal. `accept` orienta al selector del navegador, pero la comprobación MIME y tamaño del servidor es la validación efectiva.

### `admin/seccion/pedidos.php`

- Si llega `POST`, convierte el ID a entero y toma el estado enviado.
- Define una lista permitida: `Pendiente`, `Preparando`, `Entregado`.
- `in_array($estado, $estadosPermitidos, true)` comprueba que el estado pertenezca a la lista, con comparación estricta.
- Si es válido, ejecuta `UPDATE pedidos SET estado = ? WHERE id = ?`.
- Redirige a la misma página y termina para evitar reenviar el formulario.
- En una carga normal consulta `SELECT * FROM pedidos ORDER BY fecha DESC`.
- Por cada pedido consulta nombre, cantidad y subtotal desde `detalle_pedido` filtrando por `pedido_id`.
- Presenta datos y productos escapando el texto con `htmlspecialchars`; convierte la cantidad a entero para mostrarla.

Al final, incluye `admin/template/cabecera.php`, que en el estado actual destruye la sesión y redirige, así que la tabla tampoco llega a mostrarse normalmente.

**Observación importante para la defensa:** el archivo actual no inicia sesión ni comprueba `admin_logueado` antes de procesar un `POST`. Por eso, una solicitud directa puede intentar cambiar un estado sin autenticación. No se debe afirmar que todas las secciones administrativas están protegidas. Es una diferencia que hay que corregir: agregar al inicio el control de sesión usado en `admin/index.php` y `admin/seccion/productos.php`, y validar el ID. Además, el archivo incluido al final tiene que ser una cabecera visual, no un cierre de sesión.

### `admin/cerrar.php`

Si no hay sesión activa, la inicia; luego pone `$_SESSION` en un array vacío, elimina la cookie de sesión si PHP la usa, llama a `session_destroy()`, redirige al login y termina.

`session_destroy()` borra los datos del servidor; la eliminación de cookie borra la referencia del navegador. Son pasos relacionados pero distintos. `admin/cerrar.php` hace este cierre y redirige a una ruta absoluta escrita como `/panaderia/admin/login.php`, que depende de que el proyecto esté instalado en esa ruta. Hay una segunda copia de lógica de cierre en `admin/template/cabecera.php`; como los paneles incluyen esa plantilla con `require`, terminan cerrando la sesión y redirigiendo en vez de mostrar la interfaz.

## 7. Base de datos y consultas SQL

El volcado crea la base `panaderia2` y estas tablas:

| Tabla | Qué representa | Columnas importantes |
|---|---|---|
| `productos` | Catálogo de venta. | `id`, `nombre`, `precio`, `categoria`, `imagen`, fechas de creación y actualización. |
| `pedidos` | Cabecera de una compra. | `id`, `email`, `total`, `estado`, `fecha`. |
| `detalle_pedido` | Productos que forman cada pedido. | `pedido_id`, `producto_id`, nombre copiado, cantidad, precio y subtotal. |
| `mensajes` | Mensajes enviados desde contacto. | `nombre`, `email`, `mensaje`, fecha. |
| `administradores` | Usuarios que acceden al panel. | `usuario` único y hash de contraseña. |

### Relaciones

- Un pedido puede tener varios registros de detalle: `detalle_pedido.pedido_id` referencia `pedidos.id`.
- Cada detalle referencia un producto mediante `detalle_pedido.producto_id`.
- `ON DELETE CASCADE` en la relación con pedidos elimina sus detalles si se elimina el pedido.
- `ON DELETE RESTRICT` en la relación con productos evita eliminar un producto que ya figura en un detalle histórico.
- `ON UPDATE CASCADE` propaga cambios de clave a los detalles relacionados.
- La tabla `productos` tiene ID autoincremental; las claves primarias identifican filas y los índices aceleran búsquedas y garantizan unicidad cuando corresponda.

`nombre_producto`, `precio` y `subtotal` se copian al detalle del pedido. Así se conserva lo que se compró aunque el catálogo cambie después. `producto_id` conserva además la referencia al producto.

### Qué significa cada operación usada

- `SELECT columnas FROM tabla`: consulta y devuelve filas. `SELECT *` significa todas las columnas.
- `DISTINCT`: quita valores duplicados en el resultado, usado para listar categorías únicas.
- `WHERE condición`: filtra filas; `WHERE id = ?` busca por clave.
- `ORDER BY columna DESC`: ordena de mayor a menor; en un ID autoincremental suele mostrar primero registros recientes.
- `COUNT(*)`: cuenta filas que cumplen el filtro.
- `INSERT INTO tabla (columnas) VALUES (...)`: agrega una fila.
- `UPDATE tabla SET ... WHERE ...`: modifica filas; el `WHERE` es crucial para no cambiar todas las filas.
- `DELETE FROM tabla WHERE ...`: elimina filas y también debe usar un filtro adecuado.
- `?` y `:nombre` son parámetros de una consulta preparada; no son texto que se concatena manualmente.
- Clave primaria (`PRIMARY KEY`): identifica una fila de forma única.
- Clave foránea (`FOREIGN KEY`): impone que una referencia apunte a un registro existente.
- `DECIMAL(10,2)`: tipo numérico apropiado para importes con dos posiciones decimales; es preferible a guardar dinero como texto.

El backup también ejecuta `START TRANSACTION` y `COMMIT` alrededor de la importación. Las instrucciones `SET` y los comentarios de phpMyAdmin ajustan el entorno de restauración y no son consultas de negocio de las páginas.

## 8. HTML, CSS, JavaScript y Bootstrap

- **HTML** describe la estructura: encabezados, enlaces, formularios, botones, tablas e imágenes.
- **CSS** define presentación visual. `styles.css` contiene estilos propios para marca, login, tablero, formularios, miniaturas y pie administrativo.
- **Bootstrap** aporta clases ya preparadas para grillas (`container`, `row`, `col-*`), botones, alertas, tablas, navegación y diseño responsive.
- **JavaScript** en `productos.php` agrega la búsqueda inmediata en el navegador. No reemplaza validaciones del servidor ni la consulta SQL.
- **Font Awesome** aporta íconos mediante una hoja cargada desde CDN; `integrity` y `crossorigin` se relacionan con la carga verificable del recurso externo.
- Las imágenes son archivos estáticos. En las tarjetas, `alt` es texto alternativo útil para accesibilidad y cuando la imagen no puede mostrarse.

`bootstrap.min.css` es una versión comprimida/minificada de una biblioteca: los nombres de clases se usan en las páginas, pero el archivo es demasiado grande y no es razonable explicarlo línea por línea en una defensa del código propio.

## 9. Preguntas frecuentes para practicar

**¿Qué es `isset` y por qué aparece?**  
Comprueba que una clave o variable esté definida y no sea `null`. Se usa antes de leer una acción enviada, comprobar si hay carrito o detectar una imagen. Evita tratar como presente un dato que no llegó.

**¿En qué se diferencian `isset` y `empty`?**  
`isset` pregunta si existe y no es `null`; `empty` también considera vacíos valores como cadena vacía o cero. Se elige según lo que se necesita comprobar.

**¿Por qué hay tantos `if`?**  
Para decidir el flujo según condiciones: sesión autenticada o no, formulario enviado o no, carrito vacío o no, datos válidos o no, producto existente o no y operación exitosa o fallida.

**¿Por qué `SELECT` y no `INSERT`?**  
`SELECT` lee información; `INSERT` agrega una fila. `UPDATE` cambia filas existentes y `DELETE` las elimina. Se usa cada verbo según la operación de la pantalla.

**¿Qué hace `prepare()`?**  
Prepara la estructura SQL con marcadores. Luego `execute()` envía los valores por separado. Es más seguro y claro que armar SQL concatenando entradas.

**¿Qué diferencia hay entre `fetch()` y `fetchAll()`?**  
`fetch()` obtiene una fila por llamada; `fetchAll()` obtiene todas las filas restantes. En el catálogo se usa `fetchAll()` porque se presenta toda una categoría; en búsquedas de un producto se usa `fetch()`.

**¿Para qué sirve `htmlspecialchars` si los datos vienen de mi base?**  
La base puede contener texto ingresado por usuarios o administradores. Al imprimirlo en HTML hay que tratarlo como texto, no confiar en él como marcado seguro.

**¿Por qué el carrito va en una sesión?**  
Permite recordar temporalmente la selección de cada visitante entre páginas, sin crear un pedido en la base hasta que confirme la compra.

**¿Por qué consultar nuevamente el precio al pagar?**  
El navegador no es confiable y el precio puede cambiar. La base es la fuente de verdad para el importe del pedido.

**¿Por qué guardar el detalle además del pedido?**  
El pedido guarda información general (cliente, fecha, total, estado); el detalle guarda cada artículo, su cantidad y su precio. Así un pedido puede tener muchos productos.

**¿Qué hace `try/catch` en el pago?**  
Agrupa una operación que puede fallar. Si hay una excepción, `catch` ejecuta la reversión de la transacción y comunica un error general.

**¿Qué es CRUD?**  
Create, Read, Update, Delete. En el administrador de productos son alta, listado/lectura, edición y eliminación.

**¿El buscador consulta a la base?**  
No: oculta o muestra tarjetas que ya están cargadas en el navegador. La consulta a la base ocurre cuando se genera la página.

**¿Se procesa el pago con tarjeta?**  
No se observa una pasarela ni cobro electrónico. El botón registra un pedido con estado `Pendiente`; el texto «Proceder a pagar» no significa que exista integración de pago.

**¿El formulario de contacto envía un email?**  
El código visible inserta el mensaje en la tabla `mensajes`. No se observa una función de envío de correo.

## 10. Límites y puntos que conviene explicar con honestidad

1. **Plantilla administrativa mal nombrada/ubicada:** `admin/template/cabecera.php` ejecuta cierre de sesión y redirección. Como las páginas administrativas la incluyen al generar su vista, las páginas no se muestran normalmente. Conviene mover la lógica de logout a `admin/cerrar.php` y crear una cabecera HTML real.
2. **Protección del panel:** `admin/index.php` y `admin/seccion/productos.php` comprueban la sesión, pero `admin/seccion/pedidos.php` no comprueba autenticación antes de aceptar cambios. Debe protegerse antes de publicar el sitio.
3. **Cifrado del formulario del carrito:** cifrar los campos ocultos no los vuelve confiables; una clave en el código y AES-ECB no sustituyen validación ni autenticación. El pago sí vuelve a consultar los precios en la base.
4. **Contacto:** el navegador no exige nombre, pero el servidor sí lo exige; el email se comprueba por tipo HTML, pero el servidor solo comprueba longitud en `send.php`.
5. **Imágenes subidas:** el código valida MIME y tamaño, lo cual es mejor que confiar solo en la extensión. En producción también conviene restringir el acceso/ejecución en el directorio, verificar errores de `mkdir`, considerar límites del servidor y no dejar archivos huérfanos al reemplazar imágenes.
6. **Credenciales de base:** el archivo de conexión muestra parámetros apropiados al entorno local de desarrollo. En producción deben guardarse fuera del código público y configurarse con credenciales limitadas.
7. **CSRF:** en los formularios administrativos que cambian datos no se observa un token anti-CSRF. Para una versión desplegada conviene agregarlo.
8. **Errores y validación:** los controles del navegador mejoran la experiencia, pero el servidor debe validar siempre. En especial, cada acción administrativa debe comprobar sesión, autorización, ID válido y resultado de la operación.
9. **Datos de ejemplo:** el volcado contiene filas de demostración. En una defensa no hace falta mostrar ni repetir emails u otros datos personales del backup; explicar la estructura es suficiente.

## 11. Guion breve para iniciar la defensa oral

> «El proyecto es una tienda web para una panadería. Las páginas públicas se generan con PHP y HTML; Bootstrap y CSS se ocupan de la presentación. MySQL guarda el catálogo, los pedidos, sus detalles, los mensajes y las cuentas administrativas. El catálogo consulta los productos y los agrupa por categoría. El carrito se mantiene en una sesión. Al confirmar, el servidor valida el email y las cantidades, vuelve a consultar los precios reales y registra cabecera y detalles dentro de una transacción. El panel está pensado para administrar productos y estados de pedidos. Al estudiar el código encontré dos problemas que debo reconocer: la plantilla llamada cabecera administrativa actualmente cierra la sesión, y la página de gestión de pedidos no verifica autenticación antes de procesar cambios. Son correcciones necesarias antes de considerar funcional y seguro el panel.»

## 12. Orden sugerido de estudio

1. Aprender el mapa de archivos y explicar qué es PHP ejecutado en servidor frente a JavaScript ejecutado en navegador.
2. Seguir el flujo de compra sin memorizar etiquetas: catálogo → sesión/carrito → validación → transacción → pedido.
3. Practicar CRUD de productos y asociar cada acción con `SELECT`, `INSERT`, `UPDATE` o `DELETE`.
4. Dibujar las relaciones entre `pedidos`, `detalle_pedido` y `productos`.
5. Repasar conceptos de lenguaje: `if`, `isset`, `empty`, `foreach`, arrays, `include`, `require`, `POST`, sesión y redirección.
6. Explicar medidas de seguridad y sus límites: consultas preparadas, escape HTML, validación, hash de contraseña y control de sesión.
7. Practicar las preguntas frecuentes y ser transparente sobre lo que el proyecto no hace (por ejemplo, no cobra electrónicamente).

La mejor defensa no es decir que el sistema es perfecto: es explicar qué hace cada parte, por qué está separada así, cómo se protegen los datos y qué mejoras concretas se implementarían.
