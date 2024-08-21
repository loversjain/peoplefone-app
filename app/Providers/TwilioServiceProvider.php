<?php


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Twilio\Rest\Client;

class TwilioServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(Client::class, function ($app) {
            return new Client(
                env('TWILIO_SID'),  // Corrected key
                env('TWILIO_TOKEN')  // Corrected key
            );
        });
    }

}
