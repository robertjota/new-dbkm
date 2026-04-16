# DBKM - Admin Panel for KumbiaPHP

**Version:** 2.0.0  
**Compatibility:** KumbiaPHP + PHP 8.0 - 8.5

## Description

DBKM is a modern admin panel based on KumbiaPHP Framework, designed to start new projects quickly with security, elegance, and best development practices.

## Features

### Security
- ✅ Authentication with bcrypt (automatic migration from SHA1)
- ✅ Secure session management
- ✅ CSRF tokens in forms
- ✅ ACL (Access Control List)
- ✅ Access audit

### Frontend
- ✅ Bootstrap 5 (CoreUI Template)
- ✅ jQuery 3.6.1
- ✅ DataTables
- ✅ Select2
- ✅ SweetAlert2
- ✅ Font Awesome 6

### Scaffold Generator (RoboFile)

Automatic CRUD generator for the admin module:

```bash
# From default folder
cd default

# Generate CRUD with specific fields
php ../vendor/bin/robo crud product "code,name,category_id,unit_id,description,stock,price"

# Or use interactive mode
php ../vendor/bin/robo scaffold
```

#### Generated Structure

The command automatically creates:

```
default/
├── app/
│   ├── controllers/admin/
│   │   └── products_controller.php
│   ├── models/admin/
│   │   └── product.php
│   └── views/admin/products/
│       ├── listar.phtml      # DataTable with dropdown actions
│       ├── agregar.phtml     # Create form
│       ├── editar.phtml     # Edit form
│       ├── ver.phtml        # View details
│       └── form.phtml       # Form fields
```

#### Generator Features

| Feature | Description |
|---------|-------------|
| **Model** | Class with capital (Product), lowercase file |
| **Controller** | before_filter, page_module, security keys |
| **Listar** | DataTables, dropdown with View/Edit/Delete |
| **Forms** | Detects `_id` → dbSelect, `cost` → number |
| **SweetAlert** | Confirmation on delete |

#### Automatic Field Detection

The generator detects field types:
- `*_id` → `DwForm::dbSelect` (relationships)
- `cost`, `price`, `amount` → `DwForm::number`
- `stock`, `quantity` → `DwForm::number`  
- Others → `DwForm::text`

#### Security Keys

The controller automatically generates:
- `shw_{model}` - View
- `upd_{model}` - Edit
- `del_{model}` - Delete

### DW* Components (Helpers)

These helpers extend the KumbiaPHP core libraries:

| Helper | Description |
|--------|------------|
| DwHtml | Extends Html - links, buttons |
| DwForm | Extends Form - inputs, selects, dateNew, dbSelect |
| DwPaginate | Extends Paginator - DataTables pagination |
| DwButton | Formatted buttons (save, cancel, back, report) |
| DwJs | SweetAlert2 alerts |
| DwSecurity | Security tokens |

## Installation

```bash
# 1. Clone the project
git clone https://github.com/your-repo/dbkm.git

# 2. Install dependencies
composer install

# 3. Configure database
# Edit default/app/config/databases.php

# 4. Run migrations
# Create necessary tables in MySQL

# 5. Start server
php -S localhost:8000 -t default/public
```

## Structure

```
default/
├── app/
│   ├── config/         # Configurations
│   ├── controllers/   # Controllers
│   ├── extensions/   # DW* Helpers
│   ├── libs/         # Libraries
│   ├── models/       # Models
│   └── views/       # Views
├── public/
│   ├── css/
│   ├── js/
│   └── template/   # CoreUI
├── vendor/        # Composer
└── index.php
```

## Usage Examples

### Forms with DwForm

```php
// Text input
<?php echo DwForm::text('name', ['class' => 'input-required'], $value, 'Name:'); ?>

// Select with Select2
<?php echo DwForm::dbSelect('user.profile_id', 'profile', null, 'Select...', ['class' => 'input-required'], $value, 'Profile:'); ?>

// Date input
<?php echo DwForm::dateNew('birth_date', ['class' => 'input-date'], $value, 'Date:'); ?>
```

### Buttons with DwButton

```php
<?php echo DwButton::save('Save', 'fa-save', ['class' => 'btn-success'], 'Save'); ?>
<?php echo DwButton::cancel(); ?>
<?php echo DwButton::back(); ?>
```

### Links with DwHtml

```php
<?php echo DwHtml::button('system/users/', 'List', null, 'fa-users'); ?>
<?php echo DwHtml::link('system/users/edit/1', 'Edit'); ?>
```

### Pagination with DwPaginate

```php
// In controller
$user = new User();
$users = $user->paginated('page: ' . Input::post('page'), 'per_page: 20');

// In view - DataTables
<table id="js-dataTable" class="table js-dataTable"></table>
```

### Debug with dd()

```php
// Anywhere in code
$user = new User();
dd($user->find_first(1));
```

### Export to PDF

```php
use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->render();
$dompdf->stream("document.pdf");
```

### Export to Excel

```php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello Excel!');

$writer = new Xlsx($spreadsheet);
$writer->save('file.xlsx');
```

## Included Libraries (Composer)

```json
{
    "dompdf/dompdf": "^3.1",
    "phpoffice/phpspreadsheet": "^5.5",
    "symfony/var-dumper": "^8.0"
}
```

## Requirements

- PHP 8.0 - 8.5
- MySQL 5.7+
- Apache/Nginx
- Composer

## License

BSD-3-Clause

## Credits

- KumbiaPHP Team - Framework
- CoreUI - Template
- Original DBKM based on argordmel repository