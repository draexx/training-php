# training-php

Repositorio de entrenamiento para aprender y practicar PHP.
Este proyecto cubre desde los conceptos básicos hasta temas más avanzados,
proporcionando ejemplos de código claros y concisos.

## Contenido

El repositorio está organizado en directorios, cada uno enfocado en un tema específico de PHP:

-   **01-Introduccion/**:
    -   `inicio.php`: Conceptos básicos de PHP, sintaxis inicial, variables, tipos de datos, estructuras simples.
-   **02-VariablesYConstantes/**:
    -   `variables.php`: Tipos de datos detallados, declaración, ámbito, constantes, superglobals (`$_GET`, `$_POST`, `$_SERVER`).
-   **03-EstructurasDeControl/**:
    -   `estructuras.php`: Condicionales (`if`, `else`, `elseif`, `switch`) y bucles (`for`, `while`, `do-while`, `foreach`), `break`, `continue`.
-   **04-Funciones/**:
    -   `funciones.php`: Definición, argumentos (por valor, referencia, predeterminados, tipado), valores de retorno (tipado), funciones anónimas (closures), funciones de flecha.
-   **05-Arrays/**:
    -   `arrays.php`: Creación (indexados, asociativos, multidimensionales), recorrido (`foreach`, `for`), funciones comunes (`count`, `push`, `pop`, `sort`, `map`, `filter`, `reduce`), desestructuración, operador spread.
-   **06-POO/** (Programación Orientada a Objetos):
    -   `01-clases_objetos.php`: Definición de clases, creación de objetos (instanciación), propiedades, métodos, constructor (`__construct`), destructor (`__destruct`), `$this`.
    -   `02-herencia.php`: Herencia (`extends`), `parent::`, sobrescritura de métodos, palabra clave `final`.
    -   `03-encapsulamiento.php`: Modificadores de visibilidad (`public`, `protected`, `private`). Getters y setters (implícitos).
    -   `04-polimorfismo.php`: Polimorfismo a través de herencia (clases abstractas, métodos abstractos) e interfaces.
    -   `05-clases_abstractas_e_interfaces.php`: Definición y uso de clases abstractas e interfaces, diferencias clave.
    -   `06-traits.php`: Reutilización de código con traits, resolución de conflictos, cambio de visibilidad.
    -   `07-namespaces.php`: Organización del código con espacios de nombres, importación (`use`), alias.
    -   `08-metodos_magicos.php`: Métodos mágicos comunes (`__set`, `__get`, `__isset`, `__unset`, `__call`, `__callStatic`, `__toString`, `__invoke`, `__clone`, `__debugInfo`).
    -   `09-late_static_bindings.php`: Enlaces Estáticos Tardíos (`static::` vs `self::`).
    -   `index.php`: Índice del módulo de POO.
-   **07-ManejoDeFormularios/**:
    -   `formulario.html`: Formulario HTML de ejemplo con métodos GET y POST.
    -   `procesar_formulario.php`: Script PHP para procesar datos de formularios, validación básica y sanitización.
-   **08-SesionesYCookies/**:
    -   `sesiones_inicio.php`: Inicio y establecimiento de variables de sesión (`$_SESSION`).
    -   `sesiones_pagina2.php`: Comprobación de persistencia de datos de sesión.
    -   `sesiones_cerrar.php`: Destrucción de sesiones.
    -   `cookies_ejemplo.php`: Establecimiento (`setcookie`), lectura (`$_COOKIE`) y eliminación de cookies.
-   **09-ManejoDeArchivos/**:
    -   `leer_archivos.php`: Lectura de archivos (`file_get_contents`, `file`, `fopen/fgets/fclose`, `fgetcsv`).
    -   `escribir_archivos.php`: Escritura en archivos (`file_put_contents`, `fopen/fwrite/fclose`, `fputcsv`).
    -   `directorios.php`: Operaciones con directorios (`is_dir`, `mkdir`, `rmdir`, `scandir`, `opendir/readdir/closedir`).
    -   `subida_archivos.html`: Formulario HTML para subir archivos.
    -   `procesar_subida.php`: Script PHP para manejar la subida de archivos (`$_FILES`, `move_uploaded_file`), validaciones de seguridad.
    -   `index.php`: (Opcional, si se crea un índice para este módulo)
-   **10-BasesDeDatosPHPDataObjectsPDO/**:
    -   `config_db.php`: Archivo de configuración para parámetros de conexión a la BD.
    -   `conexion_pdo.php`: Conexión a la base de datos usando PDO, manejo de errores básicos de conexión.
    -   `consultas_select_pdo.php`: Ejecución de consultas SELECT, obtención de resultados (`fetchAll`, `fetch`, modos de fetch).
    -   `consultas_insert_update_delete_pdo.php`: Ejecución de consultas INSERT, UPDATE, DELETE, `lastInsertId()`, `rowCount()`.
    -   `transacciones_pdo.php`: Manejo de transacciones (`beginTransaction`, `commit`, `rollBack`).
    -   `manejo_errores_pdo.php`: Estrategias para el manejo de errores y excepciones con PDO.
    -   `index.php`: Índice del módulo de PDO.
-   **11-ManejoDeErroresYExcepciones/**:
    -   `errores_basicos.php`: Niveles de error de PHP (Notice, Warning, Parse, Error).
    -   `set_error_handler.php`: Definición de un manejador de errores personalizado.
    -   `excepciones_try_catch.php`: Uso de bloques `try-catch-finally`, `throw`, excepciones personalizadas.
    -   `set_exception_handler.php`: Definición de un manejador de excepciones global.
    -   `index.php`: Índice del módulo de Errores y Excepciones.
-   **12-JSON/**:
    -   `json_encode.php`: Conversión de datos PHP a formato JSON.
    -   `json_decode.php`: Conversión de cadenas JSON a datos PHP.
    -   `ejemplo_api_json.php`: Ejemplo práctico simulando la exposición y consumo de una API JSON simple.
    -   `index.php`: Índice del módulo JSON.

## Cómo empezar

1.  **Clona el repositorio:**
    ```bash
    git clone https://github.com/tu-usuario/training-php.git
    cd training-php
    ```
2.  **Ejecuta los archivos PHP:**
    La mayoría de los ejemplos se pueden ejecutar directamente desde la línea de comandos si tienes PHP instalado:
    ```bash
    php nombre_del_directorio/nombre_del_archivo.php
    ```
    Para ejemplos que involucren salida HTML o interacción con un servidor web, necesitarás un entorno de servidor como XAMPP, WAMP, MAMP o el servidor web incorporado de PHP:
    ```bash
    php -S localhost:8000
    ```
    Luego, abre tu navegador y ve a `http://localhost:8000/nombre_del_directorio/nombre_del_archivo.php`.

## Contribuciones

Las contribuciones son bienvenidas. Si deseas mejorar el contenido o agregar nuevos ejemplos, por favor:

1.  Haz un fork del repositorio.
2.  Crea una nueva rama para tus cambios (`git checkout -b feature/nueva-caracteristica`).
3.  Realiza tus cambios y haz commit (`git commit -am 'Agrega nueva caracteristica'`).
4.  Empuja tus cambios a la rama (`git push origin feature/nueva-caracteristica`).
5.  Abre un Pull Request.
