# DBKM - Admin Panel para KumbiaPHP

**Versión:** 2.0.0  
**Compatibilidad:** KumbiaPHP + PHP 8.0 - 8.5

## Descripción

DBKM es un panel de administración moderno basado en KumbiaPHP Framework, diseñado para iniciar nuevos proyectos rápidamente con seguridad, elegancia y las mejores prácticas de desarrollo.

## Características

### Seguridad
- ✅ Autenticación con bcrypt (migración automática desde SHA1)
- ✅ Control de sesiones seguro
- ✅ Tokens CSRF en formularios
- ✅ ACL (Access Control List)
- ✅ Auditoría de accesos

### Frontend
- ✅ Bootstrap 5 (Template CoreUI)
- ✅ jQuery 3.6.1
- ✅ DataTables
- ✅ Select2
- ✅ SweetAlert2
- ✅ Font Awesome 6

### Scaffold Generator (RoboFile)

Generador automático de CRUD para el módulo admin:

```bash
# Desde la carpeta default
cd default

# Generar CRUD con campos específicos
php ../vendor/bin/robo crud producto "codigo,nombre,categoria_id,unidad_id,descripcion,existencia,costo"

# O usar el modo interactivo
php ../vendor/bin/robo scaffold
```

#### Estructura Generada

El comando crea automáticamente:

```
default/
├── app/
│   ├── controllers/admin/
│   │   └── productos_controller.php
│   ├── models/admin/
│   │   └── producto.php
│   └── views/admin/productos/
│       ├── listar.phtml      # DataTable con dropdown acciones
│       ├── agregar.phtml     # Formulario crear
│       ├── editar.phtml     # Formulario editar
│       ├── ver.phtml        # Ver detalles
│       └── form.phtml       # Campos del formulario
```

#### Características del Generador

| Característica | Descripción |
|----------------|-------------|
| **Modelo** | Clase con mayúscula (Producto), archivo en minúscula |
| **Controlador** |before_filter, page_module, keys de seguridad |
| **Listar** | DataTables, dropdown con Ver/Editar/Eliminar |
| **Formularios** | Detecta `_id` → dbSelect, `costo` → number |
| **SweetAlert** | Confirmación al eliminar |

#### Campos Automáticos

El generador detecta tipos de campos:
- `*_id` → `DwForm::dbSelect` (relaciones)
- `costo`, `precio`, `monto` → `DwForm::number`
- `existencia`, `cantidad`, `stock` → `DwForm::number`  
- Otros → `DwForm::text`

#### Keys de Seguridad

El controlador genera automáticamente:
- `shw_{modelo}` - Ver
- `upd_{modelo}` - Editar
- `del_{modelo}` - Eliminar

### Componentes DW* (Helpers)

Los helpers extiende las librería del core de KumbiaPHP:

| Helper | Descripción |
|--------|------------|
| DwHtml | Extiende Html - links, botones |
| DwForm | Extiende Form - inputs, selects, dateNew, dbSelect |
| DwPaginate | Extiende Paginator - paginación Datatables |
| DwButton | Botones formateados (save, cancel, back, report) |
| DwJs | SweetAlert2 alerts |
| DwSecurity | Tokens de seguridad |

## Instalación

```bash
# 1. Clonar el proyecto
git clone https://github.com/tu-repo/dbkm.git

# 2. Instalar dependencias
composer install

# 3. Configurar base de datos
# Editar default/app/config/databases.php

# 4. Ejecutar migraciones
# Crear las tablas necesarias en MySQL

# 5. Iniciar servidor
php -S localhost:8000 -t default/public
```

## Estructura

```
default/
├── app/
│   ├── config/         # Configuraciones
│   ├── controllers/   # Controladores
│   ├── extensions/   # Helpers DW*
│   ├── libs/        # Librerías
│   ├── models/      # Modelos
│   └── views/       # Vistas
├── public/
│   ├── css/
│   ├── js/
│   └── template/   # CoreUI
├── vendor/        # Composer
└── index.php
```

## Uso de Componentes

### Formularios con DwForm

```php
// Input texto
<?php echo DwForm::text('nombre', ['class' => 'input-required'], $valor, 'Nombre:'); ?>

// Select con Select2
<?php echo DwForm::dbSelect('usuario.perfil_id', 'perfil', null, 'Seleccione...', ['class' => 'input-required'], $valor, 'Perfil:'); ?>

// Input fecha
<?php echo DwForm::dateNew('fecha_nacimiento', ['class' => 'input-date'], $valor, 'Fecha:'); ?>
```

### Botones con DwButton

```php
<?php echo DwButton::save('Guardar', 'fa-save', ['class' => 'btn-success'], 'Guardar'); ?>
<?php echo DwButton::cancel(); ?>
<?php echo DwButton::back(); ?>
```

### Links con DwHtml

```php
<?php echo DwHtml::button('sistema/usuarios/', 'Listar', null, 'fa-users'); ?>
<?php echo DwHtml::link('sistema/usuarios/editar/1', 'Editar'); ?>
```

### Paginación con DwPaginate

```php
// En el controller
$usuario = new Usuario();
$this->usuarios = $usuario->paginated('page: ' . Input::post('page'), 'per_page: 20');

// En la vista - DataTables
<table id="js-dataTable" class="table js-dataTable"></table>
```

### Debug con dd()

```php
// En cualquier lugar del código
$usuario = new Usuario();
dd($usuario->find_first(1));
```

### Exportar a PDF

```php
use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream("documento.pdf");
```

### Exportar a Excel

```php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hola Excel!');

$writer = new Xlsx($spreadsheet);
$writer->save('archivo.xlsx');
```

## Librerías Incluidas (Composer)

```json
{
    "dompdf/dompdf": "^3.1",
    "phpoffice/phpspreadsheet": "^5.5",
    "symfony/var-dumper": "^8.0"
}
```

## Requisitos

- PHP 8.0 - 8.5
- MySQL 5.7+
- Apache/Nginx
- Composer

## Licencia

BSD-3-Clause

## Créditos

- KumbiaPHP Team - Framework
- CoreUI - Template
- Original DBKM basado en el repositorio de argordmel