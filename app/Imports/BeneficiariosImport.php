<?php

namespace App\Imports;

use App\Models\Beneficiario;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Log;

class BeneficiariosImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    /**
     * @return int
     */
    public function headingRow(): int
    {
        // Los encabezados están en la fila 3 según el análisis del Excel
        return 3;
    }
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Debug - imprimimos las claves para ver cómo están siendo leídos los encabezados
        Log::debug('Row keys: ' . json_encode(array_keys($row)));
        Log::debug('Row values: ' . json_encode($row));
        
        // Verificar si la fila está vacía o solo contiene valores '?'
        $isEmptyRow = true;
        foreach ($row as $value) {
            if (!empty($value) && $value !== '?' && $value !== null) {
                $isEmptyRow = false;
                break;
            }
        }
        
        if ($isEmptyRow) {
            Log::info('Saltando fila vacía o con solo valores "?"');
            return null;
        }
        
        // Columnas exactas del Excel según el análisis - usamos los nombres en mayúsculas porque así aparecen en el Excel
        $cedula = $this->getValueFromPossibleKeys($row, ['CI', 'ci', 'c', 'cedula', 'cédula']);
        $nombre = $this->getValueFromPossibleKeys($row, ['NOMBRES', 'nombres', 'nombre']);
        $apellido = $this->getValueFromPossibleKeys($row, ['APELLIDOS', 'apellidos', 'apellido']);
        $carrera = $this->getValueFromPossibleKeys($row, ['CARRERA', 'carrera']);
        $institucion = $this->getValueFromPossibleKeys($row, ['UNIVERSIDAD', 'universidad']);
        $barrio = $this->getValueFromPossibleKeys($row, ['BARRIO/COMPAÑÍA', 'barrio/compañía', 'barrio/compania', 'barrio']);
        $celular = $this->getValueFromPossibleKeys($row, ['CELULAR', 'celular']);
        $rendicion = $this->getValueFromPossibleKeys($row, ['RENDICION', 'rendición', 'RENDICIÓN']);
        
        // Si la cédula está en formato numérico, puede que se haya leído como un índice numérico
        if (empty($cedula)) {
            foreach ($row as $key => $value) {
                // Si parece un número de cédula (entre 5 y 10 dígitos)
                if (is_numeric($value) && strlen((string)$value) >= 5 && strlen((string)$value) <= 10) {
                    $cedula = (string)$value;
                    break;
                }
            }
        }
        
        // Intentar extraer el nombre si no lo encontramos por el encabezado
        if (empty($nombre)) {
            foreach ($row as $key => $value) {
                if (!empty($value) && is_string($value) && !is_numeric($value) && strlen($value) > 3) {
                    if (strpos(strtolower($key), 'nombre') !== false || $key === 'NOMBRES') {
                        $nombre = $value;
                        break;
                    }
                }
            }
        }
        
        // Rendición - Si tiene "PRIMERA VEZ" o similar
        $rendicion = $this->getValueFromPossibleKeys($row, ['rendicion', 'rendición']);

        // Si no hay cédula válida, saltamos esta fila
        if (empty($cedula) || $cedula === '?' || !$cedula) {
            Log::warning('Fila saltada por falta de cédula: ' . json_encode($row));
            return null;
        }

        return new Beneficiario([
            'cedula'       => $cedula,
            'nombre'       => $nombre ?? 'Sin Nombre',
            'apellido'     => $apellido ?? '',
            'carrera'      => $carrera ?? '',
            'institucion'  => $institucion ?? '',
            'barrio'       => $barrio ?? '',
            'celular'      => $celular ?? '',
            'rendicion'    => $rendicion ?? '',
            'adjudicado'   => true, // Por defecto todos los importados son adjudicados
        ]);
    }
    
    /**
     * Obtener valor de un array usando posibles claves
     *
     * @param array $row
     * @param array $possibleKeys
     * @return string|null
     */
    private function getValueFromPossibleKeys(array $row, array $possibleKeys): ?string
    {
        // Laravel Excel puede convertir los encabezados a minúsculas y reemplazar espacios con guiones bajos
        $normalizedRow = [];
        foreach ($row as $key => $value) {
            // Si el valor es '?' o NULL, convertimos a cadena vacía
            if ($value === '?' || $value === null) {
                $value = '';
            }
            
            // Clave original
            $normalizedRow[$key] = $value;
            
            // Clave en minúsculas
            $normalizedRow[strtolower($key)] = $value;
            
            // Clave con espacios reemplazados por guiones bajos
            $normalizedRow[str_replace(' ', '_', $key)] = $value;
            
            // Clave en minúsculas y con espacios reemplazados por guiones bajos
            $normalizedRow[str_replace(' ', '_', strtolower($key))] = $value;
            
            // Clave sin caracteres especiales
            $normalizedRow[preg_replace('/[^a-zA-Z0-9]/', '', $key)] = $value;
        }
        
        foreach ($possibleKeys as $key) {
            // Verificar la clave original
            if (isset($normalizedRow[$key]) && !empty($normalizedRow[$key])) {
                return trim($normalizedRow[$key]);
            }
            
            // Verificar clave en minúsculas
            $lowerKey = strtolower($key);
            if (isset($normalizedRow[$lowerKey]) && !empty($normalizedRow[$lowerKey])) {
                return trim($normalizedRow[$lowerKey]);
            }
            
            // Verificar clave con guiones bajos
            $underscoreKey = str_replace([' ', '/'], '_', $key);
            if (isset($normalizedRow[$underscoreKey]) && !empty($normalizedRow[$underscoreKey])) {
                return trim($normalizedRow[$underscoreKey]);
            }
            
            // Verificar clave en minúsculas y con guiones bajos
            $lowerUnderscoreKey = str_replace([' ', '/'], '_', strtolower($key));
            if (isset($normalizedRow[$lowerUnderscoreKey]) && !empty($normalizedRow[$lowerUnderscoreKey])) {
                return trim($normalizedRow[$lowerUnderscoreKey]);
            }
        }
        
        // Adicional: buscar por coincidencia parcial
        foreach ($possibleKeys as $searchKey) {
            foreach ($row as $key => $value) {
                if (stripos($key, $searchKey) !== false && !empty($value)) {
                    return trim($value);
                }
            }
        }
        
        return null;
    }
    
    /**
     * @return array
     */
    public function rules(): array
    {
        // No vamos a validar ningún campo porque usaremos nuestro método getValueFromPossibleKeys
        // para encontrar los valores en diferentes formatos de columnas
        return [];
    }
    
    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 1000;
    }
    
    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }
}
