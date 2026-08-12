<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Seeded Super Admin Credentials
    |--------------------------------------------------------------------------
    |
    | Used only by AdminUserSeeder to create the first admin account for
    | local development. Change these in .env for any shared or deployed
    | environment, and rotate the password after first login.
    |
    */

    'admin_email' => env('ADMIN_EMAIL', 'admin@lifestylesanitarium.test'),
    'admin_password' => env('ADMIN_PASSWORD', 'LifestyleAdmin@2026'),

];
