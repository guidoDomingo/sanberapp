<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Beneficiados de Becas Universitarias 2025 - San Bernardino</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 2rem;
            padding-bottom: 2rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .header-title {
            background: linear-gradient(135deg, #0062cc, #007bff);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .result-card {
            margin-top: 25px;
        }
        .btn-primary {
            background: #0062cc;
            border-color: #005cbf;
        }
        .btn-primary:hover {
            background: #004494;
            border-color: #003d7e;
        }
        footer {
            margin-top: 40px;
            text-align: center;
            color: #6c757d;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="header-title">
                    <h1>Lista de Beneficiados de Becas Universitarias 2025</h1>
                    <h3>San Bernardino</h3>
                    <p class="mt-2 mb-0">Consulta si has sido adjudicado con la beca</p>
                </div>

                <div id="alertContainer"></div>

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Consulta de Beneficiarios
                    </div>
                    <div class="card-body">
                        <form id="searchForm">
                            <div class="mb-3">
                                <label for="cedula" class="form-label">Número de Cédula:</label>
                                <input type="text" class="form-control" id="cedula" name="cedula" 
                                       required
                                       placeholder="Ingrese su número de cédula sin puntos">
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Buscar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="resultContainer"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('searchForm');
            const alertContainer = document.getElementById('alertContainer');
            const resultContainer = document.getElementById('resultContainer');
            
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const cedula = document.getElementById('cedula').value;
                if (!cedula) {
                    showAlert('Debe ingresar un número de cédula', 'danger');
                    return;
                }
                
                // Realizar la búsqueda
                fetch(`/?cedula=${cedula}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.encontrado) {
                            showResult(data.data);
                        } else {
                            showNotFound(cedula);
                        }
                    })
                    .catch(error => {
                        showAlert('Error al realizar la búsqueda: ' + error.message, 'danger');
                    });
            });
            
            function showAlert(message, type) {
                alertContainer.innerHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
            }
            
            function showResult(beneficiario) {
                resultContainer.innerHTML = `
                    <div class="card result-card">
                        <div class="card-header bg-success text-white">
                            Resultado de la Búsqueda
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">${beneficiario.nombre} ${beneficiario.apellido}</h5>
                            <p class="card-text">
                                <strong>Cédula:</strong> ${beneficiario.cedula}<br>
                                <strong>Carrera:</strong> ${beneficiario.carrera || 'No especificada'}<br>
                                <strong>Institución:</strong> ${beneficiario.institucion || 'No especificada'}<br>
                                ${beneficiario.barrio ? `<strong>Barrio/Compañía:</strong> ${beneficiario.barrio}<br>` : ''}
                                ${beneficiario.celular ? `<strong>Celular:</strong> ${beneficiario.celular}<br>` : ''}
                                ${beneficiario.rendicion ? `<strong>Rendición:</strong> ${beneficiario.rendicion}` : ''}
                            </p>
                            <div class="alert alert-success" role="alert">
                                <strong>¡Felicitaciones!</strong> Ha sido adjudicado. Por favor, presentarse el lunes a las 9 de la mañana en la Municipalidad de San Bernardino.
                            </div>
                        </div>
                    </div>
                `;
            }
            
            function showNotFound(cedula) {
                resultContainer.innerHTML = `
                    <div class="card result-card">
                        <div class="card-header bg-danger text-white">
                            Resultado de la Búsqueda
                        </div>
                        <div class="card-body">
                            <div class="alert alert-danger" role="alert">
                                No se encontró ningún beneficiario con la cédula: <strong>${cedula}</strong>
                            </div>
                        </div>
                    </div>
                `;
            }
        });
    </script>
    
    <footer class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <hr>
                <p>&copy; <?php echo date('Y'); ?> Municipalidad de San Bernardino - Departamento de Educación</p>
                <p>Para consultas adicionales, comuníquese al teléfono: (000) 000-0000</p>
            </div>
        </div>
    </footer>
</body>
</html>
