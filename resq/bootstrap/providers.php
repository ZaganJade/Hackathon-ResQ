<?php

use App\Providers\AppServiceProvider;
use App\Providers\ExternalApiServiceProvider;
use Laravel\Socialite\SocialiteServiceProvider;

return [
    AppServiceProvider::class,
    ExternalApiServiceProvider::class, // Task 13 - External API Services
    SocialiteServiceProvider::class,
];
