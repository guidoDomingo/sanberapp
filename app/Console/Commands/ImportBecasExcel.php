<?php

namespace App\Console\Commands;

use App\Imports\BeneficiariosImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImportBecasExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:becas-excel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importar beneficiarios de becas desde archivo Excel';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando importación de beneficiarios desde Excel...');
        
        // Ruta al archivo Excel
        $filePath = public_path('archivo/BECA 2025 - LISTA DE POSTULANTES.xlsx');
        
        if (!file_exists($filePath)) {
            $this->error('El archivo no existe en la ruta: ' . $filePath);
            return 1;
        }
        
        try {
            $this->info('Verificando estructura de la base de datos...');
            
            // Verificar si la tabla beneficiarios existe
            if (!Schema::hasTable('beneficiarios')) {
                $this->error('La tabla beneficiarios no existe. Ejecute las migraciones primero con: php artisan migrate');
                return 1;
            }
            
            // Limpiar la tabla antes de importar nuevos datos
            $this->info('Limpiando tabla beneficiarios...');
            DB::statement('DELETE FROM beneficiarios');
            $this->info('Tabla beneficiarios limpiada correctamente.');
            
            // Analizar el archivo Excel para detectar problemas
            $this->info('Analizando formato del archivo Excel...');
            
            // Limpiar los archivos de log para tener información fresca
            if (file_exists(storage_path('logs/laravel.log'))) {
                file_put_contents(storage_path('logs/laravel.log'), '');
            }
            
            // Importar datos desde el Excel
            $this->info('Importando datos desde Excel... Esto puede tomar un momento.');
            Excel::import(new BeneficiariosImport, $filePath);
            
            $count = DB::table('beneficiarios')->count();
            $this->info('Importación completada exitosamente!');
            $this->info("Se han importado $count registros.");
            
            if ($count === 0) {
                $this->warn('No se importaron registros. Verifique que el archivo Excel tenga el formato correcto.');
                $this->info('Las columnas deben tener nombres como: cedula, nombre, apellido, carrera, institucion o similares.');
                $this->info('Asegúrese de que la primera fila contiene los encabezados de las columnas.');
            }
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Error durante la importación: ' . $e->getMessage());
            $this->info('Detalles del error para ayuda técnica:');
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
