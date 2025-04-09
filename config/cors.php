<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Spécifiez explicitement votre origine React au lieu de '*'
    'allowed_origins' => ['http://localhost:3000'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // Changez ceci à true pour permettre l'envoi des cookies et des en-têtes d'authentification
    'supports_credentials' => true,
];