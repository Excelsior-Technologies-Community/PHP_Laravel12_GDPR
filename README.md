# PHP_Laravel12_GDPR

## Introduction

PHP_Laravel12_GDPR is a Laravel 12 based web application designed to demonstrate real-world implementation of GDPR (General Data Protection Regulation) principles using the `soved/laravel-gdpr` package.

The project focuses on secure user data handling, including data portability, encryption, and automated data retention. It follows modern Laravel architecture and best practices, making it suitable for learning, internships, and production-level understanding.

This system allows authenticated users to securely download their personal data after password verification, ensuring compliance with GDPR "Right to Access" requirements.

---

## Project Overview

This application demonstrates how GDPR compliance can be implemented in a Laravel 12 project using package-based integration and custom enhancements.

### Key Functionalities

- User Registration with secure data storage
- GDPR Data Download via `/gdpr/download` endpoint
- Password confirmation before data access
- Encryption of sensitive user data (SS Number)
- Automatic cleanup of inactive users using scheduler
- Event-based logging of GDPR activities

### GDPR Principles Covered

| GDPR Principle       | Implementation                         |
|----------------------|----------------------------------------|
| Right to Access      | User data download                     |
| Data Protection      | Encrypted attributes                   |
| Data Minimization    | Hidden sensitive fields                |
| Data Retention       | Auto cleanup via scheduler             |
| Security             | Password re-authentication             |

### Technologies Used

- Laravel 12
- Laravel Breeze (Authentication)
- MySQL Database
- Tailwind CSS (UI)
- soved/laravel-gdpr package

---

## Step 1: Create Laravel 12 Project

```bash
composer create-project laravel/laravel PHP_Laravel12_GDPR "12.*"
cd PHP_Laravel12_GDPR
```

---

## Step 2: Setup Database

Update `.env` file:

```env
DB_DATABASE=laravel12_gdpr
DB_USERNAME=root
DB_PASSWORD=
```

Run:

```bash
php artisan migrate
```

---

## Step 3: Install Authentication

```bash
composer require laravel/breeze --dev
php artisan breeze:install
npm install && npm run dev
php artisan migrate
```

---

## Step 4: Install GDPR Package

```bash
composer require soved/laravel-gdpr
```

---

## Step 5: Publish Config

```bash
php artisan vendor:publish --tag=gdpr-config
```

---

## Step 6: Create new migration:

```bash
php artisan make:migration add_ssnumber_to_users_table
```
File: `database/migrations/2026_03_23_123456_add_ssnumber_to_users_table.php`

Update migration:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ssnumber')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('ssnumber');
        });
    }
};
```
Run Migration:

```bash
php artisan migrate
```

---

## Step 7: Update User Model

File: `app/Models/User.php`

```php
<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

//  GDPR Imports
use Soved\Laravel\Gdpr\Portable;
use Soved\Laravel\Gdpr\Retentionable;
use Soved\Laravel\Gdpr\EncryptsAttributes;
use Soved\Laravel\Gdpr\Contracts\Portable as PortableContract;

class User extends Authenticatable implements PortableContract
{
    use HasFactory, Notifiable;

    //  Add GDPR Traits
    use Portable, Retentionable, EncryptsAttributes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'ssnumber' // optional
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    //  GDPR: Hide sensitive data
    protected $gdprHidden = ['password'];

    //  GDPR: Encrypt fields
    protected $encrypted = ['ssnumber'];

    //  GDPR: Include relations (optional)
    protected $gdprWith = [];

    /**
     * Customize downloadable data
     */
    public function toPortableArray()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'ssnumber' => $this->ssnumber, // ADD
            'created_at' => $this->created_at,
        ];
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
```

---

## Step 8: Add UI Button

File: `resources/views/dashboard.blade.php`

```blade
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Default Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>

            <!-- GDPR Section -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">GDPR Settings</h2>

                <!-- Success Message -->
                @if(session('success'))
                    <p class="text-green-600 mb-2">{{ session('success') }}</p>
                @endif

                <!-- Error Message -->
                @if($errors->any())
                    <p class="text-red-600 mb-2">{{ $errors->first() }}</p>
                @endif

                <!-- GDPR Download Form -->
                <form method="POST" action="/gdpr/download" class="space-y-4">
                    @csrf

                    <input type="password" 
                           name="password" 
                           placeholder="Enter your password"
                           class="w-full border px-4 py-2 rounded"
                           required>

                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Download My Data
                    </button>
                </form>

            </div>

        </div>
    </div>
</x-app-layout>
```
---

## Step 9: Update register.blade.php

File: `resources/views/auth/register.blade.php`

Add this after confirm password:

```blade
        <div class="mt-4">
            <x-input-label for="ssnumber" value="SS Number" />
            <x-text-input id="ssnumber"
                class="block mt-1 w-full"
                type="text"
                name="ssnumber"
                :value="old('ssnumber')" />
        </div>
```

---

## Step 10: Update Controller

File: `app/Http/Controllers/Auth/RegisteredUserController.php`

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'ssnumber' => ['nullable', 'string', 'max:255'], // ADD THIS
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'ssnumber' => $request->ssnumber, //  ADD THIS
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
```
---

## Step 11: Listen to Events

Create Event Listener:

```bash
php artisan make:listener GdprDownloadListener
```

File: `app/Listeners/GdprDownloadListener.php`

Update:

```php
<?php

namespace App\Listeners;

use Soved\Laravel\Gdpr\Events\GdprDownloaded;

class GdprDownloadListener
{
    public function handle(GdprDownloaded $event)
    {
        \Log::info('User downloaded GDPR data', [
            'user_id' => $event->user->id
        ]);
    }
}
```
---

## Step 12: Update AppServiceProvider.php

File: `app/Providers/AppServiceProvider.php`

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Soved\Laravel\Gdpr\Events\GdprDownloaded;
use App\Listeners\GdprDownloadListener;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(GdprDownloaded::class, GdprDownloadListener::class);
    }
}
```
---

## Step 13: Update bootstrap/app.php

File: `bootstrap/app.php`

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function ($schedule) {
        $schedule->command('gdpr:cleanup')->daily();
    })
    ->create();
```
---

## Step 14: Run Scheduler

Laravel scheduler works only if you run:

```bash
php artisan schedule:work
```
---

## Step 15: Data Retention (Auto Delete)

Run command:

```bash
php artisan gdpr:cleanup
```
---

## Step 16: Run Project

### Terminal 1 (Laravel Backend)

```bash
php artisan serve
```
Visit:

```bash
http://127.0.0.1:8000
```

### Terminal 2 (Frontend / Vite / Tailwind)

```bash
npm run dev
```
---

## Output

<img src="screenshots/Screenshot 2026-03-23 165935.png" width="1000">

<img src="screenshots/Screenshot 2026-03-23 170017.png" width="1000">

<img src="screenshots/Screenshot 2026-03-23 170037.png" width="1000">

<img src="screenshots/Screenshot 2026-03-23 170110.png" width="1000">

---

## Project Structure

```
PHP_Laravel12_GDPR/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── RegisteredUserController.php
│   │   │   └── Controller.php
│   │   │
│   │   └── Middleware/
│   │
│   ├── Models/
│   │   └── User.php
│   │
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   
│   │
│   └── Listeners/
│       └── GdprDownloadListener.php
│
├── bootstrap/
│   └── app.php
│
├── config/
│   ├── app.php
│   └── gdpr.php
│
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   └── add_ssnumber_to_users_table.php
│   │
│   └── seeders/
│
├── public/
│   └── index.php
│
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   │   └── register.blade.php
│   │   │
│   │   ├── dashboard.blade.php
│   │   └── layouts/
│   │
│   ├── css/
│   └── js/
│
├── routes/
│   ├── web.php
│   └── console.php
│
├── storage/
├── tests/
├── vendor/
│
├── .env
├── composer.json
├── package.json
└── README.md
```

---

Your PHP_Laravel12_GDPR Project is now ready!
