<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\RegistroHora;
use App\Observers\RegistroHoraObserver;
use App\Models\ReportesHorasExtra;
use App\Observers\ReportesHorasExtraObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        RegistroHora::observe(RegistroHoraObserver::class);   //Agregado para calcular horas extras vinculado a app/Observers/RegistroHoraObserver.php
        ReportesHorasExtra::observe(ReportesHorasExtraObserver::class); //Agregado por mi para guardar el nombre del usuario registrado
        Schema::defaultStringLength(191);
    }
}
