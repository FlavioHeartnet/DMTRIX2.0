<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => 'smtp.dmcard.com.br',
        'secret' => 'k=rsa; p=MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDzAQBqW7+zkQZuiXCNNblaw9Fc0EDRkueG2pDTBIZsbi+FMnYKZ4v+5MH4vKqlHyV+uw65Fw2kM7Lny9C34d/f1uViFk8DUb0ipVNBAFbVq9FgandcsX22xp21B73HwzTC/hpdy6enbEvnFqIkKxoVHlyF50WoNVupXvh8JEe0XwIDAQAB',
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

];
