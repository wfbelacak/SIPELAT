<?php

/**
 * Helper Functions
 * Fungsi-fungsi pembantu global
 */

// ========== Flash Message ==========

/**
 * Set flash message ke session
 */
function flash($name = '', $message = '', $type = 'success')
{
    if (!empty($name)) {
        if (!empty($message) && empty($_SESSION[$name])) {
            $_SESSION[$name] = $message;
            $_SESSION[$name . '_type'] = $type;
        } elseif (empty($message) && !empty($_SESSION[$name])) {
            $type = $_SESSION[$name . '_type'] ?? 'success';
            $message = $_SESSION[$name];

            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_type']);

            echo '<div class="alert alert-' . $type . ' alert-dismissible fade show flash-message" role="alert">';
            echo '<i class="bi bi-' . getFlashIcon($type) . ' me-2"></i>';
            echo $message;
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            echo '</div>';
        }
    }
}

/**
 * Get icon untuk flash message
 */
function getFlashIcon($type)
{
    $icons = [
        'success' => 'check-circle-fill',
        'danger'  => 'exclamation-circle-fill',
        'warning' => 'exclamation-triangle-fill',
        'info'    => 'info-circle-fill'
    ];

    return $icons[$type] ?? 'info-circle-fill';
}


// ========== Format ==========

/**
 * Format tanggal ke bahasa Indonesia
 */
function formatDate($date, $format = 'long')
{
    if (empty($date)) {
        return '-';
    }

    $timestamp = strtotime($date);

    $bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    $hari = [
        'Sunday'    => 'Minggu',
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
        'Saturday'  => 'Sabtu'
    ];

    $d = date('j', $timestamp);
    $m = $bulan[(int) date('n', $timestamp)];
    $y = date('Y', $timestamp);
    $h = $hari[date('l', $timestamp)];
    $time = date('H:i', $timestamp);

    switch ($format) {
        case 'short':
            return "$d $m $y";

        case 'long':
            return "$h, $d $m $y";

        case 'datetime':
            return "$d $m $y $time";

        case 'full':
            return "$h, $d $m $y $time";

        default:
            return "$d $m $y";
    }
}

/**
 * Format angka ke Rupiah
 */
function formatRupiah($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}


// ========== Status Badge ==========

/**
 * Generate badge HTML untuk status
 */
function statusBadge($status)
{
    $badges = [
        // Borrowing status
        'DRAFT'        => 'badge-draft',
        'MENUNGGU'     => 'badge-menunggu',
        'DISETUJUI'    => 'badge-disetujui',
        'DITOLAK'      => 'badge-ditolak',
        'DIPINJAM'     => 'badge-dipinjam',
        'DIKEMBALIKAN' => 'badge-dikembalikan',
        'SELESAI'      => 'badge-selesai',
        'DIBATALKAN'   => 'badge-dibatalkan',

        // Return status
        'DIAJUKAN'     => 'badge-diajukan',
        'DITERIMA'     => 'badge-diterima',
        'DIPERIKSA'    => 'badge-diperiksa',

        // Tool status
        'TERSEDIA'     => 'badge-tersedia',
        'MAINTENANCE'  => 'badge-maintenance',
        'RUSAK'        => 'badge-rusak',
        'TIDAK_AKTIF'  => 'badge-tidak-aktif',

        // User/Category status
        'AKTIF'        => 'badge-aktif',
        'NONAKTIF'     => 'badge-nonaktif',
    ];

    $class = $badges[$status] ?? 'bg-secondary';
    $label = str_replace('_', ' ', $status);

    return '<span class="badge ' . $class . '">' . $label . '</span>';
}


// ========== Code Generator ==========

/**
 * Generate kode unik untuk transaksi
 */
function generateCode($prefix = 'TRX')
{
    return $prefix . '-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
}


// ========== Menu Helper ==========

/**
 * Cek apakah menu aktif berdasarkan URL
 */
function isActiveMenu($menuName)
{
    $url = $_GET['url'] ?? '';
    $segments = explode('/', $url);
    $controller = strtolower($segments[0] ?? '');

    if (strtolower($menuName) === $controller) {
        return 'active';
    }

    return '';
}


// ========== Role Helper ==========

/**
 * Get nama role berdasarkan ID
 */
function getRoleName($roleId)
{
    $roles = [
        1 => 'Admin',
        2 => 'Petugas',
        3 => 'Peminjam'
    ];

    return $roles[$roleId] ?? 'Unknown';
}

/**
 * Cek apakah user punya role tertentu
 */
function hasRole($roles)
{
    if (!is_array($roles)) {
        $roles = [$roles];
    }

    return in_array($_SESSION['user_role'] ?? 0, $roles);
}


// ========== Condition Badge ==========

/**
 * Generate badge HTML untuk kondisi alat
 */
function conditionBadge($condition)
{
    $badges = [
        'BAIK'         => ['bg-success', 'Baik'],
        'RUSAK_RINGAN' => ['bg-warning text-dark', 'Rusak Ringan'],
        'RUSAK_BERAT'  => ['bg-danger', 'Rusak Berat'],
        'HILANG'       => ['bg-dark', 'Hilang'],
    ];

    $data = $badges[$condition] ?? ['bg-secondary', $condition];

    return '<span class="badge ' . $data[0] . '">' . $data[1] . '</span>';
}


// ========== Tool Image ==========

/**
 * Resolve the illustration asset used by the customer-facing equipment catalog.
 */
function toolImage($toolOrName)
{
    if (is_object($toolOrName) && !empty($toolOrName->image)) {
        return $toolOrName->image;
    }

    $name = is_object($toolOrName)
        ? ($toolOrName->name ?? '')
        : (string) $toolOrName;

    $name = strtolower($name);

    if (
        strpos($name, 'laptop') !== false ||
        strpos($name, 'lenovo') !== false
    ) {
        return 'laptop.svg';
    }

    if (
        strpos($name, 'proyektor') !== false ||
        strpos($name, 'projector') !== false ||
        strpos($name, 'epson') !== false
    ) {
        return 'projector.svg';
    }

    if (
        strpos($name, 'kamera') !== false ||
        strpos($name, 'camera') !== false ||
        strpos($name, 'sony') !== false
    ) {
        return 'camera.svg';
    }

    if (
        strpos($name, 'monitor') !== false ||
        strpos($name, 'lcd') !== false
    ) {
        return 'monitor.svg';
    }

    return 'box.svg';
}


// ========== CSRF ==========

/**
 * Generate CSRF token
 */
function csrfToken()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Render hidden input CSRF
 */
function csrfField()
{
    echo '<input type="hidden" name="csrf_token" value="' . csrfToken() . '">';
}

/**
 * Validate CSRF token
 */
function validateCsrf()
{
    if (
        !isset($_POST['csrf_token']) ||
        $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')
    ) {
        die('CSRF token mismatch');
    }
}


// ========== Notifications ==========

/**
 * Get notifications berdasarkan role user.
 */
function getNotifications()
{
    if (!isset($_SESSION['user_id'])) {
        return [];
    }

    $db = new Database();

    $role = $_SESSION['user_role'] ?? 0;
    $userId = $_SESSION['user_id'];

    $notifs = [];

    if (in_array($role, [1, 2])) {
        // Admin / Petugas

        // Peminjaman yang menunggu persetujuan
        $db->query(
            "SELECT COUNT(*) as cnt
             FROM borrowings
             WHERE status = 'MENUNGGU'"
        );

        $menunggu = $db->single()->cnt ?? 0;

        if ($menunggu > 0) {
            $notifs[] = [
                'text' => "$menunggu Peminjaman Baru menunggu persetujuan.",
                'link' => URLROOT . '/borrowing',
                'type' => 'warning'
            ];
        }

        // Pengembalian yang menunggu verifikasi
        $db->query(
            "SELECT COUNT(*) as cnt
             FROM returns
             WHERE status = 'DIAJUKAN'"
        );

        $returns = $db->single()->cnt ?? 0;

        if ($returns > 0) {
            $notifs[] = [
                'text' => "$returns Pengembalian menunggu verifikasi.",
                'link' => URLROOT . '/return',
                'type' => 'info'
            ];
        }

        // Peminjaman yang sudah melewati batas pengembalian
        $db->query(
            "SELECT COUNT(*) as cnt
             FROM borrowings
             WHERE status = 'DIPINJAM'
             AND due_date < CURDATE()"
        );

        $overdue = $db->single()->cnt ?? 0;

        if ($overdue > 0) {
            $notifs[] = [
                'text' => "$overdue Alat melewati batas waktu pengembalian (Overdue).",
                'link' => URLROOT . '/borrowing',
                'type' => 'danger'
            ];
        }

    } else {
        // Peminjam

        // Peminjaman yang disetujui
        $db->query(
            "SELECT COUNT(*) as cnt
             FROM borrowings
             WHERE user_id = :uid
             AND status = 'DISETUJUI'"
        );

        $db->bind(':uid', $userId);

        $disetujui = $db->single()->cnt ?? 0;

        if ($disetujui > 0) {
            $notifs[] = [
                'text' => "Hore! $disetujui peminjaman Anda disetujui. Silakan ambil alatnya.",
                'link' => URLROOT . '/borrowing',
                'type' => 'success'
            ];
        }

        // Peminjaman yang ditolak
        $db->query(
            "SELECT COUNT(*) as cnt
             FROM borrowings
             WHERE user_id = :uid
             AND status = 'DITOLAK'"
        );

        $db->bind(':uid', $userId);

        $ditolak = $db->single()->cnt ?? 0;

        if ($ditolak > 0) {
            $notifs[] = [
                'text' => "Maaf, $ditolak peminjaman Anda ditolak.",
                'link' => URLROOT . '/borrowing',
                'type' => 'danger'
            ];
        }

        // Peminjaman user yang sudah overdue
        $db->query(
            "SELECT COUNT(*) as cnt
             FROM borrowings
             WHERE user_id = :uid
             AND status = 'DIPINJAM'
             AND due_date < CURDATE()"
        );

        $db->bind(':uid', $userId);

        $overdue = $db->single()->cnt ?? 0;

        if ($overdue > 0) {
            $notifs[] = [
                'text' => "Segera kembalikan $overdue alat yang terlambat (Overdue).",
                'link' => URLROOT . '/borrowing',
                'type' => 'danger'
            ];
        }
    }

    return $notifs;
}
