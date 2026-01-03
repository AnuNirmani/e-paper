<?php

return [
    // Comma-separated list of admin emails allowed to manage users/customers/publications/copies.
    'admin_emails' => array_filter(array_map('strtolower', array_map('trim', explode(',', env('ADMIN_EMAILS', ''))))),
];
