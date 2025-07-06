@echo off
echo =======================================================
echo Analizador de Excel para Becas Universitarias 2025
echo =======================================================
echo.
echo Este script analizará la estructura de su archivo Excel
echo para determinar la fila correcta de encabezados.
echo.
echo Presione cualquier tecla para continuar...
pause > nul

php artisan import:becas-excel-test

echo.
echo =======================================================
echo Análisis completado
echo =======================================================
echo.
echo Ahora puede editar el archivo:
echo app\Imports\BeneficiariosImport.php
echo.
echo Y ajustar el valor de retorno en el método headingRow()
echo según la fila donde aparecen los encabezados (normalmente donde
echo aparecen NOMBRES, APELLIDOS, etc.)
echo.
pause
