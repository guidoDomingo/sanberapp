<?php

namespace App\Console\Commands;

use App\Imports\BeneficiariosImport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportBecasExcelTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:becas-excel-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba de importación de Excel con análisis de estructura';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando análisis de archivo Excel...');
        
        // Ruta al archivo Excel
        $filePath = public_path('archivo/BECA 2025 - LISTA DE POSTULANTES.xlsx');
        
        if (!file_exists($filePath)) {
            $this->error('El archivo no existe en la ruta: ' . $filePath);
            return 1;
        }
        
        try {
            // Analizar la estructura del Excel
            $this->info('Analizando estructura del archivo Excel...');
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            
            $this->info('Mostrando las primeras 10 filas del Excel:');
            $this->newLine();
            
            // Mostrar las primeras 10 filas para análisis
            for ($row = 1; $row <= 10; $row++) {
                $rowData = [];
                for ($col = 'A'; $col <= 'J'; $col++) {
                    $cellValue = $worksheet->getCell($col . $row)->getValue();
                    $rowData[] = $cellValue;
                }
                
                $this->info("Fila $row: " . implode(' | ', $rowData));
            }
            
            $this->newLine();
            $this->info('Basado en este análisis, ajuste el método headingRow() en BeneficiariosImport.php');
            $this->info('para indicar la fila correcta donde están los encabezados (normalmente donde aparecen NOMBRES, APELLIDOS, etc.)');
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Error durante el análisis: ' . $e->getMessage());
            return 1;
        }
    }
}
