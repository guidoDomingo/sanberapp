<?php

namespace App\Http\Controllers;

use App\Models\Beneficiario;
use Illuminate\Http\Request;

class BeneficiarioController extends Controller
{
    public function index()
    {
        return view('beneficiarios.index');
    }

    public function search(Request $request)
    {
        // Si es una solicitud GET y tiene un parámetro 'cedula' en la URL
        if ($request->isMethod('get') && $request->has('cedula')) {
            $cedula = $request->cedula;
        } 
        // Si es POST, valida como antes
        else if ($request->isMethod('post')) {
            $request->validate([
                'cedula' => 'required|string'
            ]);
            $cedula = $request->cedula;
        }
        // Si no hay cédula, redirecciona al inicio
        else {
            return redirect()->route('beneficiarios.index');
        }

        // Buscar el beneficiario
        $beneficiario = Beneficiario::where('cedula', $cedula)->first();
        
        return view('beneficiarios.index', compact('beneficiario', 'cedula'));
    }

    public function importExcel()
    {
        try {
            // Ejecutar el comando de importación
            \Illuminate\Support\Facades\Artisan::call('import:becas-excel');
            
            // Obtener el resultado del comando
            $output = \Illuminate\Support\Facades\Artisan::output();
            
            // Si hay un mensaje de error en la salida, redirigir con error
            if (str_contains($output, 'Error')) {
                return redirect()->route('beneficiarios.index')
                    ->with('error', 'Error al importar: ' . $output);
            }
            
            // Contar cuántos registros se importaron
            $count = Beneficiario::count();
            
            return redirect()->route('beneficiarios.index')
                ->with('success', "Se han importado $count beneficiarios correctamente.");
        } catch (\Exception $e) {
            return redirect()->route('beneficiarios.index')
                ->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }
}
