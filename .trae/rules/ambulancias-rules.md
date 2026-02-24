# Project Rules

The project is developed in FilamentPHP version 5+ and Laravel 12+

## Main Goal

The main goal is to develop a web platform for the management of the personnel that will attend the ambulances

## Registration

The registration on the website is free but must be accepted by the administrator

## Types of personnel

The personnel that will attend the ambulances will be of type nurse

## Calendar

A calendar will be presented where the personnel can select one or more days of the month to form part of the ambulance team
Use the package guava/calendar "<https://github.com/GuavaCZ/calendar>" to implement the calendar

## Tools

<https://github.com/Grazulex/laravel-devtoolbox>
Utliza devtoolbox para analizar la aplicación
🔎 Deep Application Scanning - Complete analysis of models, routes, services, and more
🧠 Model Introspection - Analyze Eloquent models, relationships, and usage patterns
🛣️ Route Analysis - Inspect routes, detect unused ones, and analyze middleware
📦 Service Container Analysis - Examine bindings, singletons, and providers
⚙️ Environment Auditing - Compare configuration files and detect inconsistencies
🔄 SQL Query Tracing - Monitor and analyze database queries for specific routes
📊 Multiple Export Formats - JSON, Markdown, Mermaid diagrams, and more
🛠 Developer Experience - Rich console output with actionable insights

<https://github.com/laravel/boost> laravel-boost
Providing the essential context and structure that AI needs to generate high-quality, Laravel-specific code.

<https://github.com/spatie/laravel-permission> spatie/laravel-permission
Manage user permissions and roles in a Laravel application.

## Administrator acceptance

The administrator will accept or not the request of the personnel for the day of ambulance

## Personnel view

The personnel will be able to see the calendar and see the days in which they will form part of the ambulance team. The days cannot be overlapping for the personnel

## Reserve

El personal tiene un límite global de días de ambulancia mensual determinado por el administrador. El personal no puede superar este límite.
The personnel will be able to put themselves as a reserve if a day has already been selected by another personnel

## Reserved days

The personnel can place themselves on reserve if the selected day has already been occupied previously.
