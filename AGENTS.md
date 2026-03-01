# AGENTS.md - Code Guidelines for Ambulancias

This document provides guidelines for AI agents working on this Laravel application.

## Build / Lint / Test Commands

### Running Tests
```bash
# Run all tests
php artisan test

# Run tests with compact output
php artisan test --compact

# Run specific test file
php artisan test tests/Feature/CalendarAdminActionsTest.php

# Run specific test by name
php artisan test --filter=test_name

# Run with coverage (if configured)
php artisan test --coverage
```

### Code Formatting (Pint)
```bash
# Format all PHP files
vendor/bin/pint

# Format only changed files
vendor/bin/pint --dirty

# Format with agent mode (for AI agents)
vendor/bin/pint --dirty --format agent

# Test formatting without making changes
vendor/bin/pint --test
```

### Frontend Commands
```bash
# Build frontend assets
npm run build

# Development server with hot reload
npm run dev

# Clear Vite cache if manifest error occurs
npm run build
```

### Database
```bash
# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Fresh migration + seed
php artisan migrate:fresh --seed
```

## Code Style Guidelines

### PHP Conventions
- Use PHP 8.4+ features (constructor property promotion, attributes)
- Always use curly braces for control structures, even single-line
- Use explicit return types on all methods and functions
- Use PHP type hints for all parameters

```php
// Good
public function __construct(public User $user) { }
protected function isAccessible(User $user, ?string $path = null): bool

// Avoid
protected function isAccessible($user, $path = null)
```

### Naming Conventions
- Classes: PascalCase (e.g., `AmbulanceShift`, `UserResource`)
- Methods/variables: camelCase (e.g., `getShifts()`, `$isActive`)
- Constants: UPPER_CASE (e.g., `MAX_ATTEMPTS`)
- Enum keys: TitleCase (e.g., `Pending`, `Accepted`)
- Database tables: snake_case plural (e.g., `ambulance_shifts`)

### Imports
- Use fully qualified class names or explicit imports
- Group imports: built-in → external → local
- Sort alphabetically within groups

```php
use App\Enums\ShiftStatus;
use App\Models\AmbulanceShift;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
```

### Laravel Best Practices
- Use Eloquent relationships over raw queries
- Use `firstOrCreate()` instead of `create()` when duplicates possible
- Use `whereBetween()` with string dates, not Carbon objects
- Use Form Request classes for validation
- Use `config()` not `env()` outside config files

### Database Queries
```php
// Good - Eloquent with eager loading
$shifts = AmbulanceShift::with('user')
    ->whereBetween('date', [$start, $end])
    ->get();

// Avoid DB::raw() unless necessary
```

### Error Handling
- Use try-catch for operations that may fail
- Use Laravel notifications for user feedback
- Return early with guard clauses

```php
if (! $user->is_active) {
    return;
}
// Continue with logic
```

### Testing (Pest)
- Use `livewire()` for Filament/Livewire component testing
- Use `actingAs()` for authentication
- Use `assertDatabaseHas()` for database assertions

```php
livewire(ShiftCalendarWidget::class)
    ->assertCanSeeTableRecords($shifts);

actingAs($user)->get('/dashboard');
```

### Filament Patterns
- Use static `make()` methods
- Use `Get $get` for conditional logic
- Use `state()` for computed values
- Actions: `Filament\Actions\`
- Forms: `Filament\Forms\Components\`

### Comments
- Prefer PHPDoc blocks for complex logic
- Avoid inline comments unless absolutely necessary
- Don't state the obvious

## Application Context

### Tech Stack
- PHP 8.4.18, Laravel 12, Filament 5, Livewire 4
- Pest for testing, Pint for formatting
- Tailwind CSS v4, Guava Calendar

### Project Structure
```
app/
  Filament/          # Admin panels, widgets, resources
  Http/              # Controllers, middleware
  Models/            # Eloquent models
  Enums/             # PHP enums
  Notifications/     # Notification classes
database/
  migrations/
  seeders/
  factories/
tests/
  Feature/          # Integration tests
  Unit/             # Unit tests
```

### Environment
- Served via Laravel Herd at `https://ambulancias.test`
- MySQL database
- Email via Resend
