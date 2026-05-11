<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Manila');

define('APP_NAME', 'Barangay Information System - Barangay TRX');
define('BARANGAY_NAME', 'Barangay TRX');
define('DEFAULT_MUNICIPALITY', 'TRX Municipality');
define('DEFAULT_PROVINCE', 'TRX Province');

$documentRoot = isset($_SERVER['DOCUMENT_ROOT'])
    ? str_replace('\\', '/', (string) realpath($_SERVER['DOCUMENT_ROOT']))
    : '';

$projectRoot = str_replace('\\', '/', (string) realpath(__DIR__ . '/..'));
$computedBaseUrl = '/barangay_trx_full_project';

if ($documentRoot !== '' && strpos($projectRoot, $documentRoot) === 0) {
    $computedBaseUrl = str_replace('\\', '/', substr($projectRoot, strlen($documentRoot)));
}

define('BASE_URL', rtrim($computedBaseUrl, '/'));

function url(string $path = ''): string
{
    $base = rtrim(BASE_URL, '/');
    $path = ltrim($path, '/');

    return $path === ''
        ? ($base !== '' ? $base : '/')
        : (($base !== '' ? $base : '') . '/' . $path);
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

function is_post(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function get_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    $sessionToken = $_SESSION['csrf_token'] ?? '';

    if ($token === '' || $sessionToken === '' || !hash_equals($sessionToken, $token)) {
        http_response_code(419);
        exit('Invalid form token.');
    }
}

function selected($value, $current): string
{
    return (string) $value === (string) $current ? 'selected' : '';
}

function format_full_name(array $person): string
{
    return trim(
        preg_replace(
            '/\s+/',
            ' ',
            implode(' ', array_filter([
                trim((string) ($person['first_name'] ?? '')),
                trim((string) ($person['middle_name'] ?? '')),
                trim((string) ($person['last_name'] ?? '')),
                trim((string) ($person['suffix'] ?? '')),
            ]))
        )
    );
}

/* Added back because client/blotter.php and other pages use this */
function format_date_human(?string $date): string
{
    if (empty($date)) {
        return 'N/A';
    }

    $timestamp = strtotime($date);
    return $timestamp ? date('F j, Y', $timestamp) : (string) $date;
}

function format_datetime_human(?string $dateTime): string
{
    if (empty($dateTime)) {
        return 'N/A';
    }

    $timestamp = strtotime($dateTime);
    return $timestamp ? date('F j, Y g:i A', $timestamp) : (string) $dateTime;
}
?>