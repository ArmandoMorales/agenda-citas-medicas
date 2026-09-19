# Agenda de Citas Médicas

Sistema de agenda de citas médicas con calendario interactivo (FullCalendar), API REST en Laravel y base de datos MySQL ejecutándose en Docker.

## Estado del proyecto

Este proyecto se construye siguiendo un flujo de trabajo por ramas de feature, cada una fusionada a `main` mediante Pull Request:

- `feature/docker-mysql-schema` — Docker Compose con MySQL, migraciones y datos semilla.
- `feature/api-rest-citas` — Endpoints REST de citas, doctores y pacientes.
- `feature/validacion-conflictos-estados` — Validación de doble reserva y manejo de estados.
- `feature/fullcalendar-ui` — Interfaz de calendario interactivo con FullCalendar.

Ver [EVIDENCIA.md](EVIDENCIA.md) para capturas, comandos ejecutados y salidas de la API.

## Stack técnico

- **Backend:** PHP 8 + Laravel (arquitectura en capas: Controllers → Services → Repositories → Models)
- **Base de datos:** MySQL 8 (contenedor Docker con volumen persistente)
- **Frontend:** Blade + FullCalendar (JS) consumiendo la API REST

## Puesta en marcha

```bash
# 1. Levantar MySQL en Docker
docker compose up -d

# 2. Instalar dependencias del backend
cd backend
composer install
cp .env.example .env
php artisan key:generate

# 3. Ejecutar migraciones y datos semilla
php artisan migrate --seed

# 4. Levantar el servidor de la aplicación
php artisan serve
```

La aplicación quedará disponible en `http://127.0.0.1:8000` (calendario) y la API en `http://127.0.0.1:8000/api`.
