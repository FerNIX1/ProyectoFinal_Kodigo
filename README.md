# Documentación de Endpoints de la API

## General

- **Punto de entrada**
  - Todos los endpoints deben estar precedidos de la siguiente URL:
  - https://103.89.13.32/api/ 

- **GET /test (https://103.89.13.32/api/test)**
  - Descripción: Endpoint de prueba.
  - No recibe argumentos.
  - Devuelve una respuesta 200 si la API esta funcionando.

- **GET /user (https://103.89.13.32/api/user)**
  - Descripción: Retorna la información del usuario autenticado.
  - No recibe argumentos.
  - Los valores retornados son los mismos introducidos en el metodo POST.

## Autenticación

- **POST /auth/register (https://103.89.13.32/api/auth/register)**
  - Descripción: Registra un nuevo usuario.
  - Devuelve la información del usuario creado menos la contraseña si la operación fue exitosa.
  - Argumentos:
    | Argumento  | Descripción |
    | ------------- |:-------------|
    | name | Requerido, cadena de caracteres, máximo 255 caracteres, único en la tabla de usuarios, solo puede contener letras, números, guiones y guiones bajos |
    | email | Requerido, debe ser un correo electrónico válido, único en la tabla de usuarios |
    | password | Requerido, cadena de caracteres, mínimo 6 caracteres |
    | role | Opcional, cadena de caracteres, máximo 255 caracteres |
    | nombre | Opcional, cadena de caracteres, máximo 255 caracteres |
    | apellido | Opcional, cadena de caracteres, máximo 255 caracteres |
    | phone | Opcional, cadena de caracteres, máximo 20 caracteres |
    | dui | Opcional, cadena de caracteres, máximo 15 caracteres |
    | address | Opcional, cadena de caracteres, máximo 255 caracteres |
    | city | Opcional, cadena de caracteres, máximo 255 caracteres |
    | zipcode | Opcional, cadena de caracteres, máximo 10 caracteres |
    | paymethod | Opcional, cadena de caracteres, máximo 255 caracteres |
    | deleted| Indica si el producto está eliminado. Opcional, se maneja internamente como 0 si no se proporciona.|

- **GET /auth/login (https://103.89.13.32/api/auth/login)**

  - Descripción: Muestra un mensaje indicando que la autenticación es requerida en caso el usuario quiera acceder a un recurso para el que no esta autorizado.
  - No recibe argumentos.
  
- **POST /auth/login (https://103.89.13.32/api/auth/login)**
  - Descripción: Autentica a un usuario y proporciona un token de autenticacion.
  - Argumentos:
    | Argumento  | Descripción |
    | ------------- |:-------------|
    | email | Requerido, debe ser un correo electrónico válido, único en la tabla de usuarios |
    | password | Requerido, cadena de caracteres, mínimo 6 caracteres |
 
  - Contenido de la respuesta:
    | Argumento  | Descripción |
    | ------------- |:-------------|
    | access_token  | El token de acceso proporcionado al usuario en formato JWT.         |
    | token_type    | El tipo de token, en este caso es un 'bearer'.       |
    | expires_in    | El tiempo en segundos hasta que el token expira.     |
    | status        | Un valor booleano que indica el éxito de la operación. |
    | message       | Un mensaje que indica que el inicio de sesión fue exitoso. |

- **POST /auth/logout (https://103.89.13.32/api/auth/logout**
  - Descripción: Cierra sesión del usuario autenticado y muestra un mensaje indicando si fue existoso.
  - No recibe argumentos.

## Productos

- **GET /products/ (https://103.89.13.32/api/products)**
  - Descripción: Recupera todos los productos en base a los parametros de busqueda.
  - Los parametros de busqueda deben estar <a href="https://developers.google.com/maps/url-encoding?hl=es-419" target="_blank">codificados en formato URL</a> antes de ser enviados a la API.
  - Se pueden encadenar dos o mas parametros de busqueda utilizando el simbolo ampersand (&).
  - Ejemplo: https://103.89.13.32/api/products?category=frutas&name=banana
  - Parametros de busqueda:
    |Parámetro|Descripción|Ejemplo|
    |---------|-----------|-------|
    | (Ninguno)| Devuelve todos los productos existentes y que no esten desactivados.|https://103.89.13.32/api/products|
    | category| Filtra los productos por la categoría especificada.|https://103.89.13.32/api/products?category=frutas|
    | name| Busca productos que contengan en su nombre el texto especificado.|https://103.89.13.32/api/products?name=banana|
    | make| Busca productos que contengan en su marca/fabricante el texto especificado.|https://103.89.13.32/api/products?make=Chiquita|
    | model| Busca productos que contengan en su modelo el texto especificado.|https://103.89.13.32/api/products?model=Cavendish|
    | color| Busca productos que contengan en su color el texto especificado.|https://103.89.13.32/api/products?color=yellow|
    | creator_user_id| Filtra los productos por el ID del usuario creador.|https://103.89.13.32/api/products?creator_user_id=1
    | availability| Filtra los productos que están disponibles si el valor es 'true'.|https://103.89.13.32/api/products?availability=true

- **POST /products/new (https://103.89.13.32/api/products/new)**
  - Descripción: Crea un nuevo producto.
  - Devuelve la información del producto creado si la operación fue exitosa.
  - Argumentos:
    | Argumento | Descripción |
    | --------- | ----------- |
    | name| El nombre del producto. Requerido, debe ser una cadena de caracteres de máximo 255 caracteres.|
    | description| La descripción del producto. Opcional, cadena de caracteres de máximo 255 caracteres.|
    | category| La categoría del producto. Requerido, debe ser una cadena de caracteres de máximo 255 caracteres.|
    | price| El precio del producto. Requerido, debe ser numérico.|
    | stock| La cantidad de stock del producto. Requerido, debe ser numérico.|
    | img_url| La URL de la imagen del producto. Opcional, cadena de caracteres de máximo 255 caracteres.|
    | color| El color del producto. Opcional, cadena de caracteres de máximo 50 caracteres.|
    | make| La marca o fabricante del producto. Opcional, cadena de caracteres de máximo 255 caracteres.|
    | model| El modelo del producto. Opcional, cadena de caracteres de máximo 255 caracteres.|
    | availability| La disponibilidad del producto. Requerido, debe ser booleano.|
    | keywords| Las palabras clave asociadas al producto. Opcional, cadena de caracteres de máximo 255 caracteres.|
    | creator_user_id| El ID del usuario creador del producto. Requerido, debe ser entero.|
    | deleted| Indica si el producto está eliminado. Opcional, se maneja internamente como 0 si no se proporciona.|

- **GET /products/{id} (https://103.89.13.32/api/products/1)**
  - Descripción: Recupera un producto por su ID y devuelve sus valores.
  - El ID debe ser un numero entero.
  - Los valores retornados son los mismos introducidos en el metodo POST.

- **PATCH /products/{id} (https://103.89.13.32/api/products/1)**
  - Descripción: Actualiza un producto por su ID.
  - Se puede actualizar cualquier argumento incluido en el metodo POST
  - Tambien se pueden borrar productos con este metodo. Para hacerlo, basta con colocar el parametro delete despues del ID del producto
  - Ejemplo: https://103.89.13.32/api/products/1?delete=true
  - El usuario debe estar autenticado para ejecutar esta funcion y solo podran borrar pedidos los mismos usuarios que lo crearon y los administradores.

## Pedidos

- **GET /orders/ (https://103.89.13.32/api/orders)**
  - Descripción: Recupera todos los pedidos del usuario autenticado.
  - Los administradores tambien pueden adquirir pedidos de otros usuarios.
  - Los parametros de busqueda funcionan de forma similar que la busqueda de productos.
  - Se pueden encadenar dos o mas parametros de busqueda utilizando el simbolo ampersand (&).
  - Ejemplo: https://103.89.13.32/api/orders?user_id=1&completed=false
  - Parametros de busqueda:
    | Parámetro| Descripción | Ejemplo |
    |----------|-------------|---------|
    | (Ninguno)| Devuelve todos los pedidos.|https://103.89.13.32/api/orders|
    | pedido_id| Filtra los pedidos por el identificador único del pedido.|https://103.89.13.32/api/orders?pedido_id=1|
    | producto_id| Filtra los pedidos que contienen el identificador del producto especificado.|https://103.89.13.32/api/orders?producto_id=1|
    | user_id| Filtra los pedidos que contienen el identificador del usuario especificado.|https://103.89.13.32/api/orders?user_id=1|
    | completed| Filtra los pedidos basándose en si han sido completados o no.|https://103.89.13.32/api/orders?completed=true|
    | cancelled| Filtra los pedidos basándose en si han sido cancelados o no.|https://103.89.13.32/api/orders?cancelled=false|
    | wishlist| Filtra los pedidos basándose en si están en la lista de deseos o no.|https://103.89.13.32/api/orders?wishlist=true|

- **POST /orders/new (https://103.89.13.32/api/orders/new)**
  - Descripción: Crea un nuevo pedido.
  - Argumentos:
    | Argumento | Descripción |
    | --------- | ----------- |
    | pedido_id | Requerido, debe ser una cadena de caracteres única en la tabla de pedidos, máximo 255 caracteres. |
    | producto_id  | Requerido, debe ser una cadena de caracteres, máximo 255 caracteres. |
    | user_id | Requerido, debe ser una cadena de caracteres, máximo 255 caracteres. |
    | amount | Requerido, debe ser una cadena de caracteres, máximo 255 caracteres. |
    | completed | Opcional, debe ser un booleano, su valor por defecto es falso.|
    | cancelled | Opcional, debe ser un booleano, su valor por defecto es falso. |
    | wishlist | Opcional, debe ser un booleano, su valor por defecto es falso. |

- **PATCH /orders/{id} ((https://103.89.13.32/api/orders/1)**
  - Descripción: Actualiza un pedido por su ID.
  - Se puede actualizar cualquier argumento incluido en el metodo POST
  - Tambien se pueden borrar pedidos con este metodo. Para hacerlo, basta con colocar el parametro delete despues del ID del pedido
  - Ejemplo: https://103.89.13.32/api/orders/1?delete=true
  - El usuario debe estar autenticado para ejecutar esta funcion y solo podran borrar pedidos los mismos usuarios que lo crearon y los administradores. Esto tambien asigna automaticamente el argumento cancelled como verdadero al pedido.

## Perfil

- **GET /profile/ (https://103.89.13.32/api/profile/)**
  - Descripción: Devuelve la informacion del usuario autenticado.
  - Devuelve los mismos campos que en el metodo POST del registro de usuarios.

- **GET /profile/{id} (https://103.89.13.32/api/profile/1)**
  - Descripción: Recupera un usuario por su ID y muestra sus datos.
  - Funciona igual que la funcion anterior pero retornando la informacion del ID especificado.

- **PATCH /profile/{id} (https://103.89.13.32/api/profile/1)**
  - Descripción: Actualiza un usuario por su ID.
  - Se puede actualizar cualquier argumento incluido en el metodo POST
  - Tambien se pueden borrar usuarios con este metodo. Para hacerlo, basta con colocar el parametro delete despues del ID del pedido
  - Ejemplo: https://103.89.13.32/api/profile/1?delete=true
  - El usuario debe estar autenticado para ejecutar esta funcion y solo podran hacerlo el usuario dueño del perfil y los administradores.