<?php

namespace App\Providers;

use App\Models\Ticket;
use App\Policies\v1\TicketPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        if(request()->segment(2) === 'v1'){
            Gate::policy(Ticket::class, TicketPolicy::class);
        }else{
            Gate::policy(Ticket::class, \App\Policies\v2\TicketPolicy::class);
        }


    }
}
