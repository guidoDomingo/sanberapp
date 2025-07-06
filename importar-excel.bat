@echo off
echo =======================================================
echo Importador de Becas Universitarias 2025 - San Bernardino
echo =======================================================
echo.

echo IMPORTANTE: Este script importara datos del archivo Excel:
echo public/archivo/BECA 2025 - LISTA DE POSTULANTES.xlsx
echo.
echo Configuración para este Excel específico:
echo 1. La fila 3 contiene los encabezados: NRO, NOMBRES, APELLIDOS, CI, etc.
echo 2. Los datos inician en la fila 4
echo.
pause

echo Verificando base de datos SQLite...
IF NOT EXIST "database\database.sqlite" (
    echo Creando archivo SQLite...
    copy NUL database\database.sqlite
    echo Archivo de base de datos SQLite creado.
) ELSE (
    echo Base de datos SQLite existente.
)

echo.
echo Ejecutando migraciones...
php artisan migrate:fresh

echo.
echo Importando datos desde Excel...
php artisan import:becas-excel

echo.
echo =======================================================
echo Proceso completado!
echo Puede acceder a la aplicacion en: http://sanberapp.test/beneficiarios
echo =======================================================

pause
