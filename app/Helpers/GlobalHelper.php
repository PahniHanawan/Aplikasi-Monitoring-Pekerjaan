<?php

namespace App\Helpers;

class GlobalHelper
{
    public static function getFileIcon($filename)
    {
        if (!$filename) return 'fa-file';

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        switch ($extension) {
            case 'pdf':
                return 'fa-file-pdf text-danger';
            case 'doc':
            case 'docx':
                return 'fa-file-word text-primary';
            case 'xls':
            case 'xlsx':
                return 'fa-file-excel text-success';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'bmp':
            case 'svg':
                return 'fa-file-image text-info';
            case 'zip':
            case 'rar':
            case '7z':
                return 'fa-file-archive text-warning';
            default:
                return 'fa-file text-secondary';
        }
    }

    public static function getJenisDokumenLabel($jenis)
    {
        switch ($jenis) {
            case 'bast_i':
                return 'BAST I';
            case 'bast_ii':
                return 'BAST II';
            case 'kwitansi':
                return 'Kwitansi';
            case 'spp':
                return 'SPP';
            default:
                return ucfirst(str_replace('_', ' ', $jenis));
        }
    }

    public static function getJenisDokumenColor($jenis)
    {
        switch ($jenis) {
            case 'bast_i':
                return 'primary';
            case 'bast_ii':
                return 'info';
            case 'kwitansi':
                return 'success';
            case 'spp':
                return 'warning';
            default:
                return 'secondary';
        }
    }

public static function formatFileSize($bytes)
{
    if (empty($bytes) || !is_numeric($bytes)) {
        return '0 Bytes';
    }

    // Convert to integer
    $bytes = (int) $bytes;

    if ($bytes == 0) {
        return '0 Bytes';
    }

    $k = 1024;
    $sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];

    // Handle the case when $bytes < 1
    if ($bytes < $k) {
        return $bytes . ' ' . $sizes[0];
    }

    // Safe calculation
    $i = floor(log($bytes) / log($k));
    $i = min($i, count($sizes) - 1); // Ensure we don't exceed array bounds

    return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
}

    public static function isImage($filename)
    {
        if (!$filename) return false;

        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        return in_array($extension, $imageExtensions);
    }

    public static function formatCurrency($amount, $withSymbol = true)
    {
        if ($amount === null || $amount === '' || $amount === 0) {
            return $withSymbol ? 'Rp 0' : '0';
        }

        $formatted = 'Rp ' . number_format((float) $amount, 0, ',', '.');
        return $withSymbol ? $formatted : number_format((float) $amount, 0, ',', '.');
    }

    public static function cleanCurrencyInput($value)
    {
        if (empty($value)) {
            return 0;
        }

        // Jika sudah angka, langsung return
        if (is_numeric($value)) {
            return (float) $value;
        }

        // Hapus semua karakter non-digit
        $cleaned = preg_replace('/[^\d]/', '', (string) $value);

        return $cleaned ? (float) $cleaned : 0;
    }

    public static function getProgressColor($percentage)
    {
        if ($percentage >= 100) return 'success';
        if ($percentage >= 75) return 'info';
        if ($percentage >= 50) return 'primary';
        if ($percentage >= 25) return 'warning';
        return 'danger';
    }

    public static function getBadgeForRole($role)
    {
        $badges = [
            'admin' => 'badge bg-danger',
            'staff' => 'badge bg-info',
        ];

        return $badges[$role] ?? 'badge bg-secondary';
    }

    public static function getIconForRole($role)
    {
        $icons = [
            'admin' => 'bi-shield-check',
            'staff' => 'bi-person',
        ];

        return $icons[$role] ?? 'bi-person';
    }

    public static function getBagianColor($bagian)
    {
        $colors = [
            'Perencana' => 'primary',
            'Pelaksana' => 'success',
            'Pengawas' => 'secondary',
            'Pengelola Teknis' => 'info',
        ];

        return $colors[$bagian] ?? 'secondary';
    }

    public static function validateFileUpload($file, $allowedMimes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'], $maxSize = 10240)
    {
        if (!$file || !$file->isValid()) {
            return ['success' => false, 'message' => 'File tidak valid'];
        }

        // Cek ukuran file (dalam KB)
        if ($file->getSize() > $maxSize * 1024) {
            return ['success' => false, 'message' => 'Ukuran file maksimal ' . $maxSize . 'KB'];
        }

        // Cek tipe file
        $extension = $file->getClientOriginalExtension();
        if (!in_array(strtolower($extension), $allowedMimes)) {
            return ['success' => false, 'message' => 'Format file tidak didukung. Gunakan: ' . implode(', ', $allowedMimes)];
        }

        return ['success' => true, 'message' => 'File valid'];
    }

                public static function terbilangSingkat($number)
    {
        if (!is_numeric($number) || $number == 0) {
            return '';
        }

        $number = (int) $number;

        if ($number < 1000) {
            return $number . ' Rupiah';
        } elseif ($number < 1000000) {
            $ribu = $number / 1000;
            return number_format($ribu, $ribu == floor($ribu) ? 0 : 1) . ' Ribu';
        } elseif ($number < 1000000000) {
            $juta = $number / 1000000;
            return number_format($juta, $juta == floor($juta) ? 0 : 2) . ' Juta';
        } elseif ($number < 1000000000000) {
            $miliar = $number / 1000000000;
            return number_format($miliar, $miliar == floor($miliar) ? 0 : 2) . ' Miliar';
        } else {
            $triliun = $number / 1000000000000;
            return number_format($triliun, $triliun == floor($triliun) ? 0 : 2) . ' Triliun';
        }
    }

    /**
     * Mengonversi angka menjadi terbilang Rupiah (lengkap)
     * Contoh: 1525000 -> "1 Juta 525 Ribu Rupiah"
     */
    public static function terbilangRupiah($number)
    {
        if (!is_numeric($number) || $number == 0) {
            return 'Nol Rupiah';
        }

        $number = (int) $number;

        if ($number < 1000) {
            return $number . ' Rupiah';
        } elseif ($number < 1000000) {
            $ribu = floor($number / 1000);
            $sisa = $number % 1000;
            $result = $ribu . ' Ribu';
            if ($sisa > 0) {
                $result .= ' ' . $sisa;
            }
            return $result . ' Rupiah';
        } elseif ($number < 1000000000) {
            $juta = floor($number / 1000000);
            $sisa = $number % 1000000;
            $result = $juta . ' Juta';

            if ($sisa >= 1000) {
                $ribu = floor($sisa / 1000);
                $sisaRibu = $sisa % 1000;
                $result .= ' ' . $ribu . ' Ribu';
                if ($sisaRibu > 0) {
                    $result .= ' ' . $sisaRibu;
                }
            } elseif ($sisa > 0) {
                $result .= ' ' . $sisa;
            }

            return $result . ' Rupiah';
        } elseif ($number < 1000000000000) {
            $miliar = floor($number / 1000000000);
            $sisa = $number % 1000000000;
            $result = $miliar . ' Miliar';

            if ($sisa >= 1000000) {
                $juta = floor($sisa / 1000000);
                $sisaJuta = $sisa % 1000000;
                $result .= ' ' . $juta . ' Juta';

                if ($sisaJuta >= 1000) {
                    $ribu = floor($sisaJuta / 1000);
                    $sisaRibu = $sisaJuta % 1000;
                    $result .= ' ' . $ribu . ' Ribu';

                    if ($sisaRibu > 0) {
                        $result .= ' ' . $sisaRibu;
                    }
                } elseif ($sisaJuta > 0) {
                    $result .= ' ' . $sisaJuta;
                }
            } elseif ($sisa >= 1000) {
                $ribu = floor($sisa / 1000);
                $sisaRibu = $sisa % 1000;
                $result .= ' ' . $ribu . ' Ribu';

                if ($sisaRibu > 0) {
                    $result .= ' ' . $sisaRibu;
                }
            } elseif ($sisa > 0) {
                $result .= ' ' . $sisa;
            }

            return $result . ' Rupiah';
        } else {
            $triliun = floor($number / 1000000000000);
            $sisa = $number % 1000000000000;
            $result = $triliun . ' Triliun';

            if ($sisa >= 1000000000) {
                $miliar = floor($sisa / 1000000000);
                $sisaMiliar = $sisa % 1000000000;
                $result .= ' ' . $miliar . ' Miliar';

                if ($sisaMiliar >= 1000000) {
                    $juta = floor($sisaMiliar / 1000000);
                    $sisaJuta = $sisaMiliar % 1000000;
                    $result .= ' ' . $juta . ' Juta';

                    if ($sisaJuta >= 1000) {
                        $ribu = floor($sisaJuta / 1000);
                        $sisaRibu = $sisaJuta % 1000;
                        $result .= ' ' . $ribu . ' Ribu';

                        if ($sisaRibu > 0) {
                            $result .= ' ' . $sisaRibu;
                        }
                    } elseif ($sisaJuta > 0) {
                        $result .= ' ' . $sisaJuta;
                    }
                } elseif ($sisaMiliar >= 1000) {
                    $ribu = floor($sisaMiliar / 1000);
                    $sisaRibu = $sisaMiliar % 1000;
                    $result .= ' ' . $ribu . ' Ribu';

                    if ($sisaRibu > 0) {
                        $result .= ' ' . $sisaRibu;
                    }
                } elseif ($sisaMiliar > 0) {
                    $result .= ' ' . $sisaMiliar;
                }
            } elseif ($sisa >= 1000000) {
                $juta = floor($sisa / 1000000);
                $sisaJuta = $sisa % 1000000;
                $result .= ' ' . $juta . ' Juta';

                if ($sisaJuta >= 1000) {
                    $ribu = floor($sisaJuta / 1000);
                    $sisaRibu = $sisaJuta % 1000;
                    $result .= ' ' . $ribu . ' Ribu';

                    if ($sisaRibu > 0) {
                        $result .= ' ' . $sisaRibu;
                    }
                } elseif ($sisaJuta > 0) {
                    $result .= ' ' . $sisaJuta;
                }
            } elseif ($sisa >= 1000) {
                $ribu = floor($sisa / 1000);
                $sisaRibu = $sisa % 1000;
                $result .= ' ' . $ribu . ' Ribu';

                if ($sisaRibu > 0) {
                    $result .= ' ' . $sisaRibu;
                }
            } elseif ($sisa > 0) {
                $result .= ' ' . $sisa;
            }

            return $result . ' Rupiah';
        }
    }

}
