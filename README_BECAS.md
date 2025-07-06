# Sistema de Consulta de Beneficiarios de Becas Universitarias 2025 - San Bernardino

Este sistema permite importar datos de beneficiarios desde un archivo Excel y ofrecer una interfaz de búsqueda por cédula.

## Requisitos

- PHP 7.4 o superior con extensión SQLite habilitada
- Composer
- Laragon (recomendado)

## Instalación

1. Coloca el archivo Excel con la lista de beneficiarios en `public/archivo/BECA 2025 - LISTA DE POSTULANTES.xlsx`
2. Abre la consola de Laragon
3. Ejecuta el archivo batch para configurar todo:
   ```
   cd c:\laragon\www\sanberapp
   import_becas.bat
   ```

## Uso del Sistema

1. Accede a la aplicación en: http://sanberapp.test/beneficiarios
2. Ingresa el número de cédula en el campo de búsqueda
3. Si la persona está en la lista, se mostrará un mensaje confirmando que ha sido adjudicada

## Importación Manual

Si necesitas importar los datos manualmente, sigue estos pasos:

1. Abre la consola de Laragon
2. Ejecuta:
   ```
   cd c:\laragon\www\sanberapp
   php artisan migrate      # Crea las tablas necesarias
   php artisan import:becas-excel   # Importa los datos del Excel
   ```

## Estructura de Columnas del Excel

El sistema buscará las siguientes columnas en el archivo Excel (puede usar cualquiera de estas variaciones):

- Cédula: 'cedula', 'cédula', 'documento', 'nro_documento', 'nro_cedula', 'ci'
- Nombre: 'nombre', 'nombres', 'first_name', 'name'
- Apellido: 'apellido', 'apellidos', 'last_name', 'surname'
- Carrera: 'carrera', 'curso', 'programa', 'career'
- Institución: 'institucion', 'institución', 'universidad', 'college', 'institucion_educativa'

Los campos obligatorios son Cédula y Nombre.
