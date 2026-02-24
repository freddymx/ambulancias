# Ambulancias

Plataforma web para la gestión del personal de enfermería que atenderá las ambulancias.

## Objetivos del Proyecto

El objetivo principal es desarrollar una plataforma centralizada que permita:
- La gestión eficiente del personal de ambulancias (enfermeros/as).
- La organización de turnos y guardias mediante un calendario interactivo.
- El control administrativo sobre las solicitudes y asignaciones.

## Características Principales

### Gestión de Personal
- **Registro de Usuarios**: El registro es libre, pero requiere aprobación explícita por parte de un administrador para acceder al sistema.
- **Roles**: El sistema está enfocado en personal de tipo **Enfermero/a**.

### Calendario y Turnos
- **Calendario Interactivo**: Implementado con `guava/calendar`, permite al personal visualizar y seleccionar días de guardia.
- **Selección de Días**: El personal puede solicitar uno o varios días del mes para formar parte del equipo de ambulancia.
- **Validación de Superposición**: El sistema evita que un mismo usuario seleccione días que se solapen incorrectamente.

### Sistema de Reservas y Límites
- **Límite Mensual**: Existe un límite global de días de ambulancia por mes para cada usuario, determinado por el administrador.
- **Lista de Reserva**: Si un día ya ha sido seleccionado por otro usuario, el personal puede apuntarse como "reserva".

### Administración
- **Aprobación de Solicitudes**: El administrador tiene la potestad de aceptar o rechazar las solicitudes de días de ambulancia.
- **Gestión de Usuarios**: Aprobación de nuevos registros y gestión de perfiles.

## Stack Tecnológico

Este proyecto está construido con las últimas tecnologías del ecosistema Laravel:

- **Framework PHP**: [Laravel 12](https://laravel.com)
- **Panel de Administración**: [Filament v5](https://filamentphp.com)
- **Calendario**: [Guava Calendar](https://github.com/GuavaCZ/calendar)
- **Frontend**: Livewire 4 & Tailwind CSS 4
- **Base de Datos**: SQLite (por defecto) / MySQL / PostgreSQL

## Requisitos del Sistema

- PHP 8.2+
- Composer
- Node.js & NPM

## Instalación

1. Clonar el repositorio.
2. Instalar dependencias de PHP:
   ```bash
   composer install
   ```
3. Instalar dependencias de Frontend:
   ```bash
   npm install
   npm run build
   ```
4. Configurar el entorno:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
5. Ejecutar migraciones:
   ```bash
   php artisan migrate
   ```
6. Crear un usuario administrador (si es necesario):
   ```bash
   php artisan make:filament-user
   ```

## Licencia

Este proyecto es software de código abierto.
