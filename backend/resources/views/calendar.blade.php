<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de Citas Médicas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
</head>
<body>
    <header class="app-header">
        <div class="app-brand">
            <span class="app-brand-icon">🩺</span>
            <h1>Agenda de Citas Médicas</h1>
        </div>
        <div class="app-filters">
            <label for="filtro-doctor">Doctor</label>
            <select id="filtro-doctor">
                <option value="">Todos</option>
            </select>
            <button id="btn-nueva-cita" type="button" class="btn btn-primary">
                <span aria-hidden="true">+</span> Nueva cita
            </button>
        </div>
    </header>

    <main>
        <div id="calendario"></div>
    </main>

    <!-- Modal: crear cita -->
    <div id="modal-crear" class="modal-overlay hidden">
        <div class="modal">
            <h2>Nueva cita</h2>
            <form id="form-crear-cita">
                <label>Paciente
                    <select name="paciente_id" id="crear-paciente" required></select>
                </label>
                <label>Doctor
                    <select name="doctor_id" id="crear-doctor" required></select>
                </label>
                <label>Inicio
                    <input type="datetime-local" name="fecha_inicio" id="crear-fecha-inicio" required>
                </label>
                <label>Fin
                    <input type="datetime-local" name="fecha_fin" id="crear-fecha-fin" required>
                </label>
                <label>Motivo
                    <input type="text" name="motivo" id="crear-motivo" maxlength="255" required>
                </label>
                <p class="form-error" id="crear-error"></p>
                <div class="modal-actions">
                    <button type="button" class="btn" data-cerrar-modal="modal-crear">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: detalle de cita -->
    <div id="modal-detalle" class="modal-overlay hidden">
        <div class="modal">
            <h2>Detalle de la cita</h2>
            <dl class="detalle-lista">
                <dt>Paciente</dt><dd id="detalle-paciente"></dd>
                <dt>Doctor</dt><dd id="detalle-doctor"></dd>
                <dt>Horario</dt><dd id="detalle-horario"></dd>
                <dt>Motivo</dt><dd id="detalle-motivo"></dd>
                <dt>Estado</dt><dd id="detalle-estado"></dd>
            </dl>
            <p class="form-error" id="detalle-error"></p>
            <div class="modal-actions detalle-acciones">
                <button type="button" class="btn btn-confirmar" data-accion="confirmada">✓ Confirmar</button>
                <button type="button" class="btn btn-atender" data-accion="atendida">★ Marcar atendida</button>
                <button type="button" class="btn btn-cancelar" data-accion="cancelada">✕ Cancelar cita</button>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn" data-cerrar-modal="modal-detalle">Cerrar</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
