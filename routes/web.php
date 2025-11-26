<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    MiembroController,
    RutinaController,
    AsistenciaController,
    PagoController,
    ClaseVirtualController,
    AsignacionRutinaController,
    ProfileController,
    MemberDashboardController
};
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AdminDashboardController;

/*
|--------------------------------------------------------------------------
| HOME público (landing)
|--------------------------------------------------------------------------
*/
Route::view('/', 'rutinas.welcome')->name('home');

/*
|--------------------------------------------------------------------------
| LECTURA para usuarios logueados (admin + invitado + entrenador)
| Invitado puede ver: Miembros (index/show), Rutinas (index/show),
| y listado de Asignaciones (index)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'rol:admin,invitado,entrenador'])->group(function () {

    // Miembros solo lectura
    Route::get('miembros', [MiembroController::class, 'index'])->name('miembros.index');
    Route::get('miembros/{miembro}', [MiembroController::class, 'show'])
        ->whereNumber('miembro')
        ->name('miembros.show');

    // Rutinas solo lectura
    Route::get('rutinas', [RutinaController::class, 'index'])->name('rutinas.index');
    Route::get('rutinas/{rutina}', [RutinaController::class, 'show'])
        ->whereNumber('rutina')
        ->name('rutinas.show');

    // Asignaciones (solo listado)
    Route::get('asignaciones', [AsignacionRutinaController::class, 'index'])
        ->name('asignaciones.index');
});

/*
|--------------------------------------------------------------------------
| ZONA ADMIN (gestión completa)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'rol:admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        // ✅ Dashboard admin → GET /admin  (nombre: admin.dashboard)
        Route::get('/', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // ✅ Pagos → admin.pagos.*
        Route::resource('pagos', PagoController::class);

        // ✅ Miembros (gestión)
        Route::resource('miembros', MiembroController::class)->except(['index','show']);

        // ✅ Rutinas (gestión)
        Route::resource('rutinas', RutinaController::class)->except(['index','show']);

        // ✅ Clases virtuales (gestión)
        Route::resource('clases-virtuales', ClaseVirtualController::class)
            ->except(['index','show'])
            ->parameters(['clases-virtuales' => 'clase_virtual']);

        // ✅ Reportes admin
        Route::get('reportes/asistencias', [AdminReportController::class, 'asistencias'])
            ->name('reportes.asistencias');

        Route::get('reportes/finanzas', [AdminReportController::class, 'finanzas'])
            ->name('reportes.finanzas');
    });


/*
|--------------------------------------------------------------------------
| ADMIN + ENTRENADOR (gestión)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'rol:admin,entrenador'])->group(function () {

    // Asistencias
    Route::resource('asistencias', AsistenciaController::class)
        ->only(['index', 'store', 'destroy']);

    // Asignaciones CRUD excepto index/show
    Route::resource('asignaciones', AsignacionRutinaController::class)
        ->except(['index', 'show']);

    // Clases virtuales global index
    Route::get('clases-virtuales', [ClaseVirtualController::class, 'index'])
        ->name('clases-virtuales.index');

    // Rutinas CRUD (sin duplicados)
    Route::resource('rutinas', RutinaController::class)
        ->except(['index', 'show']);
});

/*
|--------------------------------------------------------------------------
| ZONA ENTRENADOR
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'rol:entrenador'])
    ->prefix('entrenador')
    ->name('entrenador.')
    ->group(function () {
        Route::view('/', 'dashboards.entrenador')->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| ZONA MIEMBRO
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'rol:miembro'])
    ->prefix('mi')
    ->name('mi.')
    ->group(function () {
        Route::get('/', [MemberDashboardController::class, 'index'])->name('dashboard');
        Route::get('asignaciones', [MemberDashboardController::class, 'misAsignaciones'])->name('asignaciones');
        Route::get('pagos', [MemberDashboardController::class, 'misPagos'])->name('pagos');
        Route::get('clases-virtuales', [MemberDashboardController::class, 'clasesVirtuales'])->name('clases');
    });

/*
|--------------------------------------------------------------------------
| PERFIL (Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
