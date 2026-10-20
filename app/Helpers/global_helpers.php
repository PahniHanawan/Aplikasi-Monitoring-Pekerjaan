<?php
// Global helper functions

if (!function_exists('getFileIcon')) {
    function getFileIcon($filename) {
        return \App\Helpers\GlobalHelper::getFileIcon($filename);
    }
}

if (!function_exists('getJenisDokumenLabel')) {
    function getJenisDokumenLabel($jenis) {
        return \App\Helpers\GlobalHelper::getJenisDokumenLabel($jenis);
    }
}

if (!function_exists('getJenisDokumenColor')) {
    function getJenisDokumenColor($jenis) {
        return \App\Helpers\GlobalHelper::getJenisDokumenColor($jenis);
    }
}

if (!function_exists('formatFileSize')) {
    function formatFileSize($bytes) {
        return \App\Helpers\GlobalHelper::formatFileSize($bytes);
    }
}

if (!function_exists('isImageFile')) {
    function isImageFile($filename) {
        return \App\Helpers\GlobalHelper::isImage($filename);
    }
}

if (!function_exists('format_currency')) {
    function format_currency($amount, $withSymbol = true) {
        return \App\Helpers\GlobalHelper::formatCurrency($amount, $withSymbol);
    }
}

if (!function_exists('clean_currency_input')) {
    function clean_currency_input($value) {
        return \App\Helpers\GlobalHelper::cleanCurrencyInput($value);
    }
}

if (!function_exists('get_progress_color')) {
    function get_progress_color($percentage) {
        return \App\Helpers\GlobalHelper::getProgressColor($percentage);
    }
}

if (!function_exists('get_badge_for_role')) {
    function get_badge_for_role($role) {
        return \App\Helpers\GlobalHelper::getBadgeForRole($role);
    }
}

if (!function_exists('get_icon_for_role')) {
    function get_icon_for_role($role) {
        return \App\Helpers\GlobalHelper::getIconForRole($role);
    }
}

if (!function_exists('get_bagian_color')) {
    function get_bagian_color($bagian) {
        return \App\Helpers\GlobalHelper::getBagianColor($bagian);
    }
}

if (!function_exists('validate_file_upload')) {
    function validate_file_upload($file, $allowedMimes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'], $maxSize = 10240) {
        return \App\Helpers\GlobalHelper::validateFileUpload($file, $allowedMimes, $maxSize);
    }
}


if (!function_exists('terbilang_singkat')) {
    function terbilang_singkat($number) {
        return \App\Helpers\GlobalHelper::terbilangSingkat($number);
    }
}

if (!function_exists('terbilang_rupiah')) {
    function terbilang_rupiah($number) {
        return \App\Helpers\GlobalHelper::terbilangRupiah($number);
    }
}

// Tambahkan function ini di helpers.php
if (!function_exists('getKeteranganRupiah')) {
    function getKeteranganRupiah($amount) {
        if (!$amount || $amount == 0) return '';

        $num = (float) $amount;

        if ($num >= 1000000000000) {
            $triliun = $num / 1000000000000;
            return ($triliun == floor($triliun))
                ? number_format($triliun, 0, ',', '.') . ' Triliun Rupiah'
                : number_format($triliun, 2, ',', '.') . ' Triliun Rupiah';
        } elseif ($num >= 1000000000) {
            $miliar = $num / 1000000000;
            return ($miliar == floor($miliar))
                ? number_format($miliar, 0, ',', '.') . ' Miliar Rupiah'
                : number_format($miliar, 2, ',', '.') . ' Miliar Rupiah';
        } elseif ($num >= 1000000) {
            $juta = $num / 1000000;
            return ($juta == floor($juta))
                ? number_format($juta, 0, ',', '.') . ' Juta Rupiah'
                : number_format($juta, 2, ',', '.') . ' Juta Rupiah';
        } elseif ($num >= 1000) {
            $ribu = $num / 1000;
            return ($ribu == floor($ribu))
                ? number_format($ribu, 0, ',', '.') . ' Ribu Rupiah'
                : number_format($ribu, 2, ',', '.') . ' Ribu Rupiah';
        } elseif ($num >= 100) {
            return number_format($num, 0, ',', '.') . ' Ratus Rupiah';
        } else {
            return number_format($num, 0, ',', '.') . ' Rupiah';
        }
    }
}


// ========== TAMBAHKAN DI LUAR FUNCTION validate_file_upload ==========

if (!function_exists('old_array')) {
    function old_array($key, $index, $fallback = null) {
        $oldData = old($key);

        // Jika old data adalah array dan index ada
        if (is_array($oldData) && isset($oldData[$index])) {
            return $oldData[$index];
        }

        // Jika bukan array, coba dengan dot notation
        $dotKey = "{$key}.{$index}";
        if (old($dotKey)) {
            return old($dotKey);
        }

        return $fallback;
    }
}

if (!function_exists('old_array_item')) {
    function old_array_item($arrayKey, $index, $itemKey, $fallback = null) {
        $oldData = old($arrayKey);

        // Jika old data adalah array multidimensi
        if (is_array($oldData) && isset($oldData[$index][$itemKey])) {
            return $oldData[$index][$itemKey];
        }

        // Coba dengan dot notation
        $dotKey = "{$arrayKey}.{$index}.{$itemKey}";
        $dotValue = old($dotKey);
        if ($dotValue !== null) {
            return $dotValue;
        }

        return $fallback;
    }
}
