<?php

namespace App\Middlewares;

use App\Core\Auth;
use Groupes;

class RouteMiddleWare
{
    public static function requireAuth(): void
    {
        if (!Auth::check()) {
            header('Location: ' . LINK . 'login');
            exit();
        }
    }

    public static function isLogged(): void
    {
        if (Auth::check()) {
            self::redirect('dashboard');
        }
    }

    public static function requireSuper(): void
    {
        self::requireAuth();
        if (!Auth::hasGroupe(Groupes::SUPER)) {
            self::redirectBack();
        }
        Auth::saveCurrentUrl();
    }

    public static function requireAdmin(): void
    {
        self::requireAuth();
        if (!Auth::hasGroupe(Groupes::ADMIN)) {
            self::redirectBack();
        }
        Auth::saveCurrentUrl();
    }

    public static function requireComptable(): void
    {
        self::requireAuth();
        if (!Auth::hasGroupe(Groupes::COMPTABLE)) {
            self::redirectBack();
        }
        Auth::saveCurrentUrl();
    }

    public static function requireGestion(): void
    {
        self::requireAuth();
        if (!Auth::hasGroupe(Groupes::GESTION)) {
            self::redirectBack();
        }
        Auth::saveCurrentUrl();
    }

    public static function requireCommercial(): void
    {
        self::requireAuth();
        if (!Auth::hasGroupe(Groupes::COMMERCIAL)) {
            self::redirectBack();
        }
        Auth::saveCurrentUrl();
    }

    public static function requireGroupe(int $groupeId): void
    {
        if (!Auth::hasGroupe($groupeId)) {
            http_response_code(403);
            die("🚫 Accès refusé : vous n'avez pas le groupe [$groupeId].");
        }
    }

    public static function requireRole(int $role): void
    {
        if (!Auth::hasGroupe($role)) {
            http_response_code(403);
            die("🚫 Accès refusé : vous n'avez pas le role [$role].");
        }
    }

    public static function requirePermission(int $roleId, string $permission): void
    {
        if (!Auth::can($roleId, $permission)) {
            http_response_code(403);
            die("🚫 Accès refusé : vous n'avez pas la permission [$permission].");
        }
    }

    public static function redirect(string $url = ''): void
    {
        if ($url === '') {
            $url = '/';
        }
        header('Location: ' . LINK . $url);
        exit();
    }

    public static function redirectTo(string $url): void
    {
        self::redirect($url);
    }

    public static function redirectBack()
    {
        $redirect = Auth::flashUrl('url') ?? '';
        self::redirect($redirect);
    }

    public static function flash(string $message, string $fallback = '/'): void
    {
        Auth::updateFlash('message', $message);
    }
}
