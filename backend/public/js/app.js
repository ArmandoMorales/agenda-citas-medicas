(function () {
    const API_BASE = '/api';

    const COLORES_ESTADO = {
        pendiente: '#f59e0b',
        confirmada: '#2563eb',
        cancelada: '#6b7280',
        atendida: '#16a34a',
    };

    const ETIQUETAS_ESTADO = {
        pendiente: 'Pendiente',
        confirmada: 'Confirmada',
        cancelada: 'Cancelada',
        atendida: 'Atendida',
    };

    const filtroDoctor = document.getElementById('filtro-doctor');
    const btnNuevaCita = document.getElementById('btn-nueva-cita');
    const modalCrear = document.getElementById('modal-crear');
    const modalDetalle = document.getElementById('modal-detalle');
    const formCrear = document.getElementById('form-crear-cita');
    const crearError = document.getElementById('crear-error');
    const detalleError = document.getElementById('detalle-error');

    let citaSeleccionada = null;
    let calendar = null;

    async function apiFetch(path, options = {}) {
        const respuesta = await fetch(API_BASE + path, {
            headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
            ...options,
        });

        const cuerpo = await respuesta.json().catch(() => ({}));

        if (!respuesta.ok) {
            const error = new Error(cuerpo.message || 'Ocurrió un error inesperado.');
            error.detalles = cuerpo.errors;
            throw error;
        }

        return cuerpo;
    }

    function abrirModal(modal) {
        modal.classList.remove('hidden');
    }

    function cerrarModal(modal) {
        modal.classList.add('hidden');
    }

    document.querySelectorAll('[data-cerrar-modal]').forEach((btn) => {
        btn.addEventListener('click', () => cerrarModal(document.getElementById(btn.dataset.cerrarModal)));
    });

    function aFechaLocalInput(fecha) {
        const pad = (n) => String(n).padStart(2, '0');
        return `${fecha.getFullYear()}-${pad(fecha.getMonth() + 1)}-${pad(fecha.getDate())}T${pad(fecha.getHours())}:${pad(fecha.getMinutes())}`;
    }

    async function cargarSelects() {
        const [doctores, pacientes] = await Promise.all([
            apiFetch('/doctores'),
            apiFetch('/pacientes'),
        ]);

        const selectDoctor = document.getElementById('crear-doctor');
        const selectPaciente = document.getElementById('crear-paciente');

        doctores.forEach((doctor) => {
            filtroDoctor.add(new Option(`${doctor.nombre} (${doctor.especialidad})`, doctor.id));
            selectDoctor.add(new Option(`${doctor.nombre} (${doctor.especialidad})`, doctor.id));
        });

        pacientes.forEach((paciente) => {
            selectPaciente.add(new Option(paciente.nombre, paciente.id));
        });
    }

    function citaAEvento(cita) {
        return {
            id: cita.id,
            title: `${cita.paciente.nombre} · ${cita.doctor.nombre}`,
            start: cita.fecha_inicio,
            end: cita.fecha_fin,
            backgroundColor: COLORES_ESTADO[cita.estado],
            borderColor: COLORES_ESTADO[cita.estado],
            classNames: cita.estado === 'cancelada' ? ['evento-cancelado'] : [],
            extendedProps: { cita },
        };
    }

    function inicializarCalendario() {
        const el = document.getElementById('calendario');

        calendar = new FullCalendar.Calendar(el, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek',
            },
            locale: 'es',
            timeZone: 'UTC',
            editable: true,
            eventDurationEditable: true,
            height: 'auto',

            events: async (info, successCallback, failureCallback) => {
                try {
                    const params = new URLSearchParams({
                        desde: info.startStr,
                        hasta: info.endStr,
                    });

                    if (filtroDoctor.value) {
                        params.set('doctor_id', filtroDoctor.value);
                    }

                    const citas = await apiFetch(`/citas?${params.toString()}`);
                    successCallback(citas.map(citaAEvento));
                } catch (error) {
                    failureCallback(error);
                }
            },

            dateClick: (info) => abrirModalCrear(info.date),

            eventClick: (info) => abrirModalDetalle(info.event.extendedProps.cita),

            eventDrop: (info) => reprogramar(info),
            eventResize: (info) => reprogramar(info),
        });

        calendar.render();
    }

    function abrirModalCrear(fechaInicial) {
        formCrear.reset();
        crearError.textContent = '';

        const inicio = fechaInicial || new Date();
        inicio.setHours(9, 0, 0, 0);
        const fin = new Date(inicio.getTime() + 30 * 60 * 1000);

        document.getElementById('crear-fecha-inicio').value = aFechaLocalInput(inicio);
        document.getElementById('crear-fecha-fin').value = aFechaLocalInput(fin);

        abrirModal(modalCrear);
    }

    btnNuevaCita.addEventListener('click', () => abrirModalCrear(new Date()));

    formCrear.addEventListener('submit', async (evento) => {
        evento.preventDefault();
        crearError.textContent = '';

        const datos = Object.fromEntries(new FormData(formCrear).entries());

        try {
            await apiFetch('/citas', { method: 'POST', body: JSON.stringify(datos) });
            cerrarModal(modalCrear);
            calendar.refetchEvents();
        } catch (error) {
            crearError.textContent = error.message;
        }
    });

    function abrirModalDetalle(cita) {
        citaSeleccionada = cita;
        detalleError.textContent = '';

        document.getElementById('detalle-paciente').textContent = cita.paciente.nombre;
        document.getElementById('detalle-doctor').textContent = `${cita.doctor.nombre} (${cita.doctor.especialidad})`;
        document.getElementById('detalle-horario').textContent =
            `${new Date(cita.fecha_inicio).toLocaleString('es-ES')} - ${new Date(cita.fecha_fin).toLocaleString('es-ES')}`;
        document.getElementById('detalle-motivo').textContent = cita.motivo;

        const badge = document.getElementById('detalle-estado');
        badge.textContent = ETIQUETAS_ESTADO[cita.estado];
        badge.className = 'estado-badge';
        badge.style.background = COLORES_ESTADO[cita.estado];
        badge.style.color = '#fff';

        abrirModal(modalDetalle);
    }

    document.querySelectorAll('#modal-detalle [data-accion]').forEach((boton) => {
        boton.addEventListener('click', async () => {
            if (!citaSeleccionada) return;
            detalleError.textContent = '';

            try {
                await apiFetch(`/citas/${citaSeleccionada.id}/estado`, {
                    method: 'PATCH',
                    body: JSON.stringify({ estado: boton.dataset.accion }),
                });
                cerrarModal(modalDetalle);
                calendar.refetchEvents();
            } catch (error) {
                detalleError.textContent = error.message;
            }
        });
    });

    async function reprogramar(info) {
        const cita = info.event.extendedProps.cita;

        try {
            await apiFetch(`/citas/${cita.id}`, {
                method: 'PUT',
                body: JSON.stringify({
                    fecha_inicio: info.event.startStr,
                    fecha_fin: info.event.endStr,
                }),
            });
            calendar.refetchEvents();
        } catch (error) {
            alert('No se pudo reprogramar: ' + error.message);
            info.revert();
        }
    }

    filtroDoctor.addEventListener('change', () => calendar.refetchEvents());

    (async function iniciar() {
        await cargarSelects();
        inicializarCalendario();
    })();
})();
