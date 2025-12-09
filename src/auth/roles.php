<?php

require_once __DIR__ . '/../models/User.php';

function hasRole(?User $user, string|int ...$roles): bool
{
    if ($user === null) {
        return false;
    }

    $roleName = $user->ruolo_nome ? strtolower($user->ruolo_nome) : null;
    $roleId = $user->ruolo_id;

    foreach ($roles as $role) {
        if (is_int($role) || ctype_digit((string) $role)) {
            if ($roleId !== null && $roleId === (int) $role) {
                return true;
            }
            continue;
        }

        if ($roleName !== null && $roleName === strtolower((string) $role)) {
            return true;
        }
    }

    return false;
}
