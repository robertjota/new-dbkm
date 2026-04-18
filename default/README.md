# DBKM - Sistema de Administración

Sistema de administración basado en KumbiaPHP con estructura modular.

## Estructura de Carpetas

```
app/
├── controllers/
│   ├── auxiliar/          # Módulo auxiliar (CRUDs)
│   ├── administracion/    # Módulo administración
│   ├── sistema/          # Módulo sistema (usuarios, menus, etc.)
│   └── dashboard/
├── models/
│   ├── auxiliar/
│   ├── administracion/
│   └── sistema/
└── views/
    ├── auxiliar/
    ├── administracion/
    ├── sistema/
    └── _shared/
```

## Convenciones

### Modelos (Singular)
- Nombre en minúsculas = nombre de la tabla
- Clase en mayúsculas primera letra
- Ubicación: `app/models/{modulo}/{singular}.php`
- Ejemplo: `Producto` → `app/models/auxiliar/producto.php`

### Controladores (Plural)
- Nombre en minúsculas plural
- Clase en mayúsculas primera letra + "Controller"
- Ubicación: `app/controllers/{modulo}/{plural}_controller.php`
- Ejemplos: `ProductosController` → `app/controllers/auxiliar/productos_controller.php`

### Vistas
- Ubicación: `app/views/{modulo}/{plural}/*.phtml`
- Archivos: `listar.phtml`, `agregar.phtml`, `editar.phtml`, `ver.phtml`

## Generador de CRUD (RoboFile)

### Comandos disponibles

```bash
# CRUD completo (modelo + controlador + vistas + form)
php robo crud "campos" singular plural [modulo]

# Solo modelo
php robo model singular [modulo]

# Solo controlador
php robo controller plural [modulo]

# Modo interactivo (preguntas)
php robo scaffold
```

### Ejemplos

```bash
# CRUD completo en módulo auxiliar (por defecto)
php robo crud "codigo,nombre" producto productos

# CRUD completo en módulo específico
php robo crud "codigo,nombre" producto productos administracion

# CRUD completo con relaciones
php robo scaffold
# Responde las preguntas interactivamente

# Solo modelo
php robo model producto auxiliar

# Solo controlador
php robo controller productos auxiliar
```

### Parámetros

| Parámetro | Descripción |
|-----------|-------------|
| `singular` | Nombre del modelo (tabla) - ej: `producto`, `unidad` |
| `plural` | Nombre del controlador/vistas - ej: `productos`, `unidades` |
| `campos` | Lista de campos separados por coma |
| `modulo` | Carpeta destino (default: `auxiliar`) |

## Sistema AJAX

### Formularios AJAX

```php
// Usar DwForm::openAjax() para formularios AJAX
<?php echo DwForm::openAjax(); ?>

// O DwForm::open() normal (recarga completa)
<?php echo DwForm::open(); ?>
```

### Botones AJAX

Los botones usan clases CSS para comportamiento:
- `js-link` - Cargar contenido vía AJAX
- `js-spinner` - Mostrar spinner durante carga
- `js-url` - Actualizar URL del navegador

### Botón con confirmación (SweetAlert2)

```php
<?php echo DwHtml::buttonTableSA('Eliminar', "auxiliar/productos/eliminar/$key/", 
    ['msg-title' => 'Eliminar', 'msg' => '¿Está seguro?'], 'danger', 'fa-trash'); ?>
```

## Rutas

### URLs del sistema

```
/{modulo}/{controlador}/{accion}/
```

Ejemplos:
- `/auxiliar/productos/listar/` - Listar productos
- `/auxiliar/productos/agregar/` - Agregar producto
- `/auxiliar/productos/editar/abc123/` - Editar producto
- `/auxiliar/productos/eliminar/abc123/` - Eliminar producto

## Configuración

### Módulo por defecto: `auxiliar`

Al crear nuevos CRuds sin especificar módulo, se crea en `auxiliar`.

### Módulos disponibles

- `auxiliar` - CRuds generados por el usuario
- `administracion` - Administración (productos, categorías, etc.)
- `sistema` - Sistema (usuarios, perfiles, menus, etc.)
- `dashboard` - Panel principal

### Datos de la Empresa

Los datos de la empresa se configuran en `config.php` en la sección `custom.empresa`:

```php
// app/config/config.php
'custom' => [
    'empresa' => [
        'nombre' => 'Mi Empresa',
        'rif' => 'J-12345678-9',
        'direccion' => 'Dirección de la empresa',
        'telefono' => '0212-1234567',
        'email' => 'correo@empresa.com',
        'web' => 'https://empresa.com',
    ],
],
```

Para acceder a los datos desde el código:

```php
// Usando Config
Config::get('custom.empresa.nombre');

// O usando el helper DwEmpresa
DwEmpresa::nombre();
DwEmpresa::rif();
DwEmpresa::direccion();
DwEmpresa::telefono();
DwEmpresa::email();
DwEmpresa::web();
DwEmpresa::logo();       // URL del logo
DwEmpresa::logoMini();   // URL del logo mini
```

Los logos se almacenan en:
- `public/empresa/logo-empresa.png` - Logo principal
- `public/empresa/logo-mini.png` - Logo para sidebar colapsado

### DwResponse - Respuestas AJAX

Para respuestas JSON en controladores AJAX:

```php
// Respuesta exitosa
DwResponse::sendSuccess('Mensaje de éxito', $data, $url, $reloadMenu);

// Ejemplo con redirección
DwResponse::sendSuccess('Guardado correctamente', null, '/modulo/controlador/listar', true);

// Respuesta de error
DwResponse::sendError('Mensaje de error');

// Respuesta de validación
DwResponse::sendValidation($errores);
```

Parámetros de `sendSuccess`:
- `$message` - Mensaje a mostrar
- `$data` - Datos adicionales (opcional)
- `$url` - URL para redirigir (opcional)
- `$reloadMenu` - true para recargar el sidebar del menú