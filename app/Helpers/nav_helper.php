<?php

use CodeIgniter\Router\RouteCollection;

/**
 * Cek apakah route saat ini aktif
 */
function set_active(string $routeName, string $className = 'active'): string
{
    $currentRoute = service('router')->getMatchedRouteOptions()['as'] ?? '';
    return ($currentRoute === $routeName) ? $className : '';
}

/**
 * Enkripsi ID berita untuk URL menggunakan base64url (URL-safe)
 */
function encrypt_berita_id(int $id): string
{
    // Enkripsi menggunakan base64 dengan tambahan salt
    $salt = 'ormaone_berita_2024';
    $data = $id . '_' . $salt;
    $encrypted = base64_encode($data);
    
    // Convert ke base64url (URL-safe): ganti + dengan -, / dengan _, dan hapus =
    $encrypted = strtr($encrypted, '+/', '-_');
    $encrypted = rtrim($encrypted, '=');
    
    return $encrypted;
}

/**
 * Dekripsi ID berita dari URL (base64url)
 */
function decrypt_berita_id(string $encrypted): ?int
{
    try {
        // Convert dari base64url ke base64: ganti - dengan +, _ dengan /, dan tambahkan padding =
        $encrypted = strtr($encrypted, '-_', '+/');
        
        // Tambahkan padding jika diperlukan
        $padding = strlen($encrypted) % 4;
        if ($padding) {
            $encrypted .= str_repeat('=', 4 - $padding);
        }
        
        $decrypted = base64_decode($encrypted, true);
        if ($decrypted === false) {
            return null;
        }
        
        $salt = 'ormaone_berita_2024';
        
        if (strpos($decrypted, '_' . $salt) !== false) {
            $id = (int)str_replace('_' . $salt, '', $decrypted);
            return $id > 0 ? $id : null;
        }
        return null;
    } catch (\Exception $e) {
        return null;
    }
}

/**
 * Enkripsi ID voting untuk URL menggunakan base64url (URL-safe)
 */
function encrypt_voting_id(int $id): string
{
    // Enkripsi menggunakan base64 dengan tambahan salt
    $salt = 'ormaone_voting_2024';
    $data = $id . '_' . $salt;
    $encrypted = base64_encode($data);
    
    // Convert ke base64url (URL-safe): ganti + dengan -, / dengan _, dan hapus =
    $encrypted = strtr($encrypted, '+/', '-_');
    $encrypted = rtrim($encrypted, '=');
    
    return $encrypted;
}

/**
 * Dekripsi ID voting dari URL (base64url)
 */
function decrypt_voting_id(string $encrypted): ?int
{
    try {
        // Convert dari base64url ke base64: ganti - dengan +, _ dengan /, dan tambahkan padding =
        $encrypted = strtr($encrypted, '-_', '+/');
        
        // Tambahkan padding jika diperlukan
        $padding = strlen($encrypted) % 4;
        if ($padding) {
            $encrypted .= str_repeat('=', 4 - $padding);
        }
        
        $decrypted = base64_decode($encrypted, true);
        if ($decrypted === false) {
            return null;
        }
        
        $salt = 'ormaone_voting_2024';
        
        if (strpos($decrypted, '_' . $salt) !== false) {
            $id = (int)str_replace('_' . $salt, '', $decrypted);
            return $id > 0 ? $id : null;
        }
        return null;
    } catch (\Exception $e) {
        return null;
    }
}