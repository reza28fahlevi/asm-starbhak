<?php

/**
 * Custom Helper Functions
 * File: app/Helpers/functions_helper.php
 * 
 * Untuk menggunakan helper ini, load di Controller atau BaseController:
 * helper('functions');
 * 
 * Atau auto-load di app/Config/Autoload.php:
 * public $helpers = ['functions'];
 */

if (!function_exists('format_date')) {
    /**
     * Format tanggal ke format Indonesia
     * 
     * @param string $date
     * @param string $format (short, long, full)
     * @return string
     */
    function format_date($date, $format = 'short')
    {
        if (empty($date) || $date == '0000-00-00' || $date == '0000-00-00 00:00:00') {
            return '-';
        }

        $timestamp = strtotime($date);
        
        switch ($format) {
            case 'short':
                // Output: 13 Jan 2026
                return date('d M Y', $timestamp);
                
            case 'long':
                // Output: 13 Januari 2026
                $bulan = [
                    1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];
                $d = date('d', $timestamp);
                $m = $bulan[(int)date('n', $timestamp)];
                $y = date('Y', $timestamp);
                return "$d $m $y";
                
            case 'full':
                // Output: Senin, 13 Januari 2026 10:30
                $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                $bulan = [
                    1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];
                $h = $hari[date('w', $timestamp)];
                $d = date('d', $timestamp);
                $m = $bulan[(int)date('n', $timestamp)];
                $y = date('Y', $timestamp);
                $t = date('H:i', $timestamp);
                return "$h, $d $m $y $t";
                
            default:
                return date('Y-m-d', $timestamp);
        }
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format angka ke format Rupiah
     * 
     * @param int|float $amount
     * @param bool $symbol
     * @return string
     */
    function format_currency($amount, $symbol = true)
    {
        $formatted = number_format($amount, 0, ',', '.');
        return $symbol ? 'Rp ' . $formatted : $formatted;
    }
}

if (!function_exists('format_number')) {
    /**
     * Format angka dengan separator
     * 
     * @param int|float $number
     * @param int $decimals
     * @return string
     */
    function format_number($number, $decimals = 0)
    {
        return number_format($number, $decimals, ',', '.');
    }
}

if (!function_exists('format_filesize')) {
    /**
     * Format ukuran file (bytes ke KB, MB, GB)
     * 
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    function format_filesize($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

if (!function_exists('generate_code')) {
    /**
     * Generate kode unik (untuk nomor asset, nomor transaksi, dll)
     * 
     * @param string $prefix
     * @param int $length
     * @return string
     */
    function generate_code($prefix = 'AST', $length = 6)
    {
        $number = str_pad(rand(1, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
        return $prefix . '-' . date('Ymd') . '-' . $number;
    }
}

if (!function_exists('generate_random_string')) {
    /**
     * Generate random string
     * 
     * @param int $length
     * @param string $type (alpha, numeric, alnum)
     * @return string
     */
    function generate_random_string($length = 10, $type = 'alnum')
    {
        switch ($type) {
            case 'alpha':
                $pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case 'numeric':
                $pool = '0123456789';
                break;
            case 'alnum':
            default:
                $pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
        }
        
        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }
}

if (!function_exists('get_current_user')) {
    /**
     * Get current logged in user data
     * 
     * @param string|null $key
     * @return mixed
     */
    function get_current_user($key = null)
    {
        $session = \Config\Services::session();
        
        if ($key) {
            return $session->get($key);
        }
        
        return [
            'user_id'  => $session->get('user_id'),
            'username' => $session->get('username'),
            'nama'     => $session->get('nama'),
            'email'    => $session->get('email'),
            'role_id'  => $session->get('role_id'),
        ];
    }
}

if (!function_exists('is_logged_in')) {
    /**
     * Check if user is logged in
     * 
     * @return bool
     */
    function is_logged_in()
    {
        $session = \Config\Services::session();
        return (bool) $session->get('logged_in');
    }
}

if (!function_exists('user_has_role')) {
    /**
     * Check if user has specific role
     * 
     * @param string|array $role
     * @return bool
     */
    function user_has_role($role)
    {
        $session = \Config\Services::session();
        $userRole = $session->get('role_name');
        
        if (is_array($role)) {
            return in_array($userRole, $role);
        }
        
        return $userRole === $role;
    }
}

if (!function_exists('status_badge')) {
    /**
     * Generate status badge HTML
     * 
     * @param bool|string $status
     * @param array $labels
     * @return string
     */
    function status_badge($status, $labels = [])
    {
        $defaultLabels = [
            'active'   => '<span class="badge bg-success">Aktif</span>',
            'inactive' => '<span class="badge bg-danger">Nonaktif</span>',
            'pending'  => '<span class="badge bg-warning">Pending</span>',
            'approved' => '<span class="badge bg-success">Disetujui</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
        ];
        
        $labels = array_merge($defaultLabels, $labels);
        
        // Boolean to string
        if (is_bool($status)) {
            $status = $status ? 'active' : 'inactive';
        }
        
        return $labels[$status] ?? '<span class="badge bg-secondary">' . ucfirst($status) . '</span>';
    }
}

if (!function_exists('active_menu')) {
    /**
     * Check if current menu is active
     * 
     * @param string $path
     * @param string $class
     * @return string
     */
    function active_menu($path, $class = 'active')
    {
        $uri = service('uri');
        $current = $uri->getPath();
        
        if ($current === $path) {
            return $class;
        }
        
        // Check if starts with path (for parent menu)
        if (strpos($current, $path) === 0) {
            return $class;
        }
        
        return '';
    }
}

if (!function_exists('truncate_text')) {
    /**
     * Truncate text dengan ellipsis
     * 
     * @param string $text
     * @param int $limit
     * @param string $ellipsis
     * @return string
     */
    function truncate_text($text, $limit = 100, $ellipsis = '...')
    {
        if (strlen($text) <= $limit) {
            return $text;
        }
        
        return substr($text, 0, $limit) . $ellipsis;
    }
}

if (!function_exists('sanitize_filename')) {
    /**
     * Sanitize filename untuk upload
     * 
     * @param string $filename
     * @return string
     */
    function sanitize_filename($filename)
    {
        // Remove extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);
        
        // Replace spaces and special chars
        $name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
        $name = preg_replace('/_+/', '_', $name);
        $name = trim($name, '_');
        
        return strtolower($name . '.' . $ext);
    }
}

if (!function_exists('get_client_ip')) {
    /**
     * Get real client IP address
     * 
     * @return string
     */
    function get_client_ip()
    {
        $ipaddress = '';
        
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }
        
        return $ipaddress;
    }
}

if (!function_exists('age_from_date')) {
    /**
     * Calculate age from date
     * 
     * @param string $date
     * @return int
     */
    function age_from_date($date)
    {
        $birthDate = new DateTime($date);
        $today = new DateTime('today');
        return $birthDate->diff($today)->y;
    }
}

if (!function_exists('time_ago')) {
    /**
     * Convert timestamp to "time ago" format
     * 
     * @param string $datetime
     * @return string
     */
    function time_ago($datetime)
    {
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;
        
        if ($diff < 60) {
            return $diff . ' detik yang lalu';
        } elseif ($diff < 3600) {
            return floor($diff / 60) . ' menit yang lalu';
        } elseif ($diff < 86400) {
            return floor($diff / 3600) . ' jam yang lalu';
        } elseif ($diff < 604800) {
            return floor($diff / 86400) . ' hari yang lalu';
        } elseif ($diff < 2592000) {
            return floor($diff / 604800) . ' minggu yang lalu';
        } elseif ($diff < 31536000) {
            return floor($diff / 2592000) . ' bulan yang lalu';
        } else {
            return floor($diff / 31536000) . ' tahun yang lalu';
        }
    }
}

if (!function_exists('indonesian_day')) {
    /**
     * Get Indonesian day name
     * 
     * @param string $date
     * @return string
     */
    function indonesian_day($date)
    {
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $dayNumber = date('w', strtotime($date));
        return $days[$dayNumber];
    }
}

if (!function_exists('indonesian_month')) {
    /**
     * Get Indonesian month name
     * 
     * @param int $month
     * @return string
     */
    function indonesian_month($month)
    {
        $months = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        return $months[(int)$month] ?? '';
    }
}

if (!function_exists('dd')) {
    /**
     * Dump and die (for debugging)
     * 
     * @param mixed ...$vars
     */
    function dd(...$vars)
    {
        echo '<pre style="background: #1e1e1e; color: #dcdcdc; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 13px;">';
        foreach ($vars as $var) {
            var_dump($var);
            echo "\n";
        }
        echo '</pre>';
        die();
    }
}

if (!function_exists('pr')) {
    /**
     * Print readable (for debugging)
     * 
     * @param mixed $var
     * @param bool $die
     */
    function pr($var, $die = false)
    {
        echo '<pre style="background: #f5f5f5; padding: 15px; border: 1px solid #ddd; border-radius: 5px; font-family: monospace; font-size: 13px;">';
        print_r($var);
        echo '</pre>';
        
        if ($die) {
            die();
        }
    }
}

if (!function_exists('flash_message')) {
    /**
     * Set flash message
     * 
     * @param string $type (success, error, warning, info)
     * @param string $message
     */
    function flash_message($type, $message)
    {
        $session = \Config\Services::session();
        $session->setFlashdata($type, $message);
    }
}

if (!function_exists('validation_errors_array')) {
    /**
     * Get validation errors as array
     * 
     * @param \CodeIgniter\Validation\Validation $validation
     * @return array
     */
    function validation_errors_array($validation)
    {
        if (!$validation->getErrors()) {
            return [];
        }
        
        return $validation->getErrors();
    }
}

if (!function_exists('clean_string')) {
    /**
     * Clean string from special characters
     * 
     * @param string $string
     * @return string
     */
    function clean_string($string)
    {
        $string = strip_tags($string);
        $string = preg_replace('/[^A-Za-z0-9\s-]/', '', $string);
        $string = preg_replace('/\s+/', ' ', $string);
        return trim($string);
    }
}

if (!function_exists('slug')) {
    /**
     * Generate URL-friendly slug
     * 
     * @param string $text
     * @return string
     */
    function slug($text)
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        
        if (empty($text)) {
            return 'n-a';
        }
        
        return $text;
    }
}

if (!function_exists('array_to_options')) {
    /**
     * Convert array to HTML select options
     * 
     * @param array $array
     * @param string $selected
     * @param string $emptyLabel
     * @return string
     */
    function array_to_options($array, $selected = '', $emptyLabel = '-- Pilih --')
    {
        $html = '<option value="">' . $emptyLabel . '</option>';
        
        foreach ($array as $key => $value) {
            $isSelected = ($key == $selected) ? 'selected' : '';
            $html .= '<option value="' . $key . '" ' . $isSelected . '>' . $value . '</option>';
        }
        
        return $html;
    }
}

if (!function_exists('departemen_options')) {
    /**
     * Get departemen options for select
     * 
     * @param string $selected
     * @return string
     */
    function departemen_options($selected = '')
    {
        $departemens = [
            1 => 'IT',
            2 => 'Finance',
            3 => 'HR',
            4 => 'Operations',
            5 => 'Marketing',
            6 => 'Sales',
        ];
        
        return array_to_options($departemens, $selected, '-- Pilih Departemen --');
    }
}

if (!function_exists('get_departemen_name')) {
    /**
     * Get departemen name by ID
     * 
     * @param int $id
     * @return string
     */
    function get_departemen_name($id)
    {
        $departemens = [
            1 => 'IT',
            2 => 'Finance',
            3 => 'HR',
            4 => 'Operations',
            5 => 'Marketing',
            6 => 'Sales',
        ];
        
        return $departemens[$id] ?? '-';
    }
}
