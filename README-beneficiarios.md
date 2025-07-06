# Aplicación de Búsqueda de Beneficiarios de Becas Universitarias 2025

Esta aplicación permite consultar si una persona ha sido adjudicada con una beca universitaria para el año 2025 en San Bernardino.

## Requisitos

- PHP 8.1 o superior
- Compositor
- Extensión PDO SQLite habilitada

## Instalación

1. Clonar el repositorio
2. Instalar dependencias con `composer install`
3. Copiar el archivo `.env.example` a `.env` y configurar
4. Crear el archivo de base de datos SQLite: `touch database/database.sqlite`
5. Ejecutar migraciones: `php artisan migrate`
6. Ejecutar seeders para cargar datos de ejemplo: `php artisan db:seed`

## Importar datos desde Excel

Para importar datos desde el archivo Excel de postulantes, ejecute:

```
php artisan import:beneficiarios
```

## Ejecutar la aplicación

```
php artisan serve
```

Luego acceda a la aplicación en http://localhost:8000/beneficiarios

## Estructura

- `app/Models/Beneficiario.php` - Modelo de beneficiarios
- `app/Http/Controllers/BeneficiarioController.php` - Controlador para la búsqueda
- `app/Console/Commands/ImportBeneficiarios.php` - Comando para importar datos desde Excel
- `resources/views/beneficiarios/index.blade.php` - Vista principal de búsqueda
- `database/migrations/*_create_beneficiarios_table.php` - Migración para la tabla
- `database/seeders/BeneficiarioSeeder.php` - Seeder con datos de ejemplo
