<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationGroup;
use Illuminate\Support\HtmlString;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('Ingenio Río Grande S.A.') // 👈 ¡ESTA LÍNEA!
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            // 👇 AGREGAR ESTO PARA EL PIE DE PÁGINA
        ->renderHook('panels::body.end', fn () => new HtmlString('
            <div style="
                position: fixed;
                bottom: 0;
                right: 0;
                padding: 0.5rem 1rem;
                background: rgba(0,0,0,0.05);
                color: #6b7280;
                font-size: 0.75rem;
                border-top-left-radius: 0.5rem;
            ">
                @MandiocaSoft
            </div>
        '))
->renderHook('panels::head.end', fn() => new HtmlString('
    <style>
        /* Cambiar color y tamaño del brand name */
        .fi-logo { 
            color: #9333ea !important; /* 👈 ¡QUITÉ EL PUNTO Y COMA EXTRA! */
            font-weight: bold;
            font-size: 2.rrem !important; /* 24px */
        }
        
        /* Para el topbar */
        .fi-topbar .fi-logo {
            color: #49394bff !important;
            font-size: 1.25rem !important; /* 20px */
        }
        
        /* Para el sidebar */
        .fi-sidebar .fi-logo {
            color: #a7703cff !important;
            font-size: 1.5rem !important; /* 18px */
        }
        
        /* Para los grupos de navegación (Administración, etc.) */
        .fi-sidebar-group-label {
            color: #e7e295ff !important;        /* Amarillo */
            font-size: 0.875rem !important;  /* 14px */
            font-weight: 600 !important;     /* Semi-bold */
            text-transform: uppercase !important; /* Mayúsculas */
        }
        
        /* Color de hover para los grupos */
        .fi-sidebar-group-label:hover {
            color: #bd9e9eff !important; /* Color hover */
        }

        /* Inscripción en el login */
        .fi-simple-layout::after {
            content: "⚙️ @MandiocaSoft (La Mendieta, Jujuy)";
            display: block;
            text-align: center;
            margin-top: 2rem;
            padding: 1rem;
            color: #9333ea;
            font-size: 0.875rem;
            font-weight: 500;
            letter-spacing: 0.05em;
        }
    </style>
    
    <!-- Cambiar favicon -->
<link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>⚙️</text></svg>">
'))





            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            // 👇 Organización del menú SIN íconos en grupos
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Escritorio'),
                    
                NavigationGroup::make()
                    ->label('Zafra'),
                    
                NavigationGroup::make()
                    ->label('Personal transitorio'),
                    
                NavigationGroup::make()
                    ->label('Administración'),
                    
                NavigationGroup::make()
                    ->label('Recursos'),
            ])
            // 👇 Personalizar el orden de los items del menú

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}