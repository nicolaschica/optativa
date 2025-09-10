<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calculadora IMC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php
session_start();
if (!isset($_SESSION['historial'])) {
    $_SESSION['historial'] = [];
}
?>

<div class="container py-5">
    <div class="row g-4 justify-content-center">
        <!-- Calculadora IMC -->
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4 text-center">Calculadora de IMC</h3>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nombre:</label>
                            <input type="text" name="nombre" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Edad:</label>
                            <input type="number" name="edad" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Género:</label>
                            <select name="genero" class="form-select">
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Peso (kg):</label>
                            <input type="number" name="peso" min="20" required class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Altura (cm):</label>
                            <input type="number" name="altura" required class="form-control">
                        </div>
                        <button type="submit" name="calcular" class="btn btn-success w-100">Calcular IMC</button>
                    </form>

                    <?php
                        if (isset($_POST['calcular'])) {
                            $nombre = $_POST['nombre'];
                            $edad = $_POST['edad'];
                            $genero = $_POST['genero'];
                            $peso = $_POST['peso'];
                            $altura = $_POST['altura'];

                            $altura_m = $altura / 100;
                            $imc = $peso / ($altura_m * $altura_m);
                            $imc_formateado = number_format($imc, 2);

                            // Inicializar variables de mensaje
                            $estado = '';
                            $mensajeDieta = '';
                            $mensajeRutina = '';
                            $claseBootstrap = 'info';

                            if ($imc < 18.5) {
                                $estado = "Bajo peso";
                                $mensajeDieta = "Aumentar calorías con proteínas y carbohidratos saludables.";
                                $mensajeRutina = "Ejercicios de fuerza para ganar masa muscular.";
                                $claseBootstrap = 'primary';
                            } elseif ($imc < 24.9) {
                                $estado = "Normal";
                                $mensajeDieta = "Mantener balance entre proteínas, verduras y carbohidratos.";
                                $mensajeRutina = "Cardio moderado + fuerza ligera.";
                                $claseBootstrap = 'success';
                            } elseif ($imc < 29.9) {
                                $estado = "Sobrepeso";
                                $mensajeDieta = "Reducir azúcares y grasas saturadas.";
                                $mensajeRutina = "Cardio frecuente y ejercicios de resistencia.";
                                $claseBootstrap = 'warning';
                            } elseif ($imc < 34.9) {
                                $estado = "Obesidad I";
                                $mensajeDieta = "Reducir azúcares, grasas saturadas y aumentar consumo de vegetales y proteínas magras.";
                                $mensajeRutina = "Actividad física moderada diaria (caminar, trotar, bicicleta) al menos 30 min.";
                                $claseBootstrap = 'warning';
                            } elseif ($imc < 39.9) {
                                $estado = "Obesidad II";
                                $mensajeDieta = "Control estricto de azúcares, grasas y harinas refinadas. Aumentar frutas, verduras y fibra.";
                                $mensajeRutina = "Cardio frecuente (correr, bicicleta, natación) complementado con ejercicios de fuerza ligera.";
                                $claseBootstrap = 'danger';
                            } else {
                                $estado = "Obesidad III";
                                $mensajeDieta = "Plan supervisado por nutricionista, bajo en calorías, alto en nutrientes esenciales.";
                                $mensajeRutina = "Ejercicio de bajo impacto (caminar, natación) con progresión controlada y supervisada médica.";
                                $claseBootstrap = 'danger';
                            }

                            $_SESSION['historial'][] = [
                                'nombre' => $nombre,
                                'edad' => $edad,
                                'genero' => $genero,
                                'peso' => $peso,
                                'altura' => $altura,
                                'imc' => $imc_formateado,
                                'estado' => $estado
                            ];
                            echo "<div class='alert alert-$claseBootstrap mt-4'>
                                <h5>Resultado para $nombre</h5>
                                <p>Edad: $edad años</p>
                                <p>Género: $genero</p>
                                <p><b>IMC: $imc_formateado</b></p>
                                <p class='fw-bold'>Estado: $estado</p>
                                <p><strong>Dieta:</strong> $mensajeDieta</p>
                                <p><strong>Rutina:</strong> $mensajeRutina</p>
                            </div>";
                        }
                        ?>
                </div>
            </div>
        </div>

        <!-- Historial -->
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4 text-center">Historial de registros</h3>

                    <?php if (!empty($_SESSION['historial'])): ?>
                        <form method="POST" class="mb-3">
                            <button type="submit" name="eliminar_todo" class="btn btn-danger w-100">Eliminar Todo</button>
                        </form>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Edad</th>
                                    <th>Género</th>
                                    <th>Peso</th>
                                    <th>Altura</th>
                                    <th>IMC</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($_SESSION['historial'])) {
                                    foreach ($_SESSION['historial'] as $registro) {
                                        echo "<tr>
                                            <td>{$registro['nombre']}</td>
                                            <td>{$registro['edad']}</td>
                                            <td>{$registro['genero']}</td>
                                            <td>{$registro['peso']}</td>
                                            <td>{$registro['altura']}</td>
                                            <td>{$registro['imc']}</td>
                                            <td>{$registro['estado']}</td>
                                        </tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
