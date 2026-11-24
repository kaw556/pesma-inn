<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;
    protected $table = 'kamars';

    protected $fillable = ['kode_kamar','jenis_kamar','gedung','harga','fasilitas','status','foto'];

    /** Nama file gambar yang dipakai halaman publik untuk suatu jenis kamar. */
    public static function fotoJenisNama(string $jenis): string
    {
        $nama = trim($jenis) === 'Standard' ? 'Standar Room' : trim($jenis);
        return preg_replace('/[^\p{L}\p{N} ._-]/u', '', $nama);
    }

    /** Path relatif gambar jenis kamar di public/, atau null jika belum ada. */
    public static function fotoJenisPath(?string $jenis): ?string
    {
        if (!$jenis) return null;

        $nama = static::fotoJenisNama($jenis);
        foreach (['jpg', 'jpeg'] as $ext) {
            $path = "images/{$nama}.{$ext}";
            if (file_exists(public_path($path))) return $path;
        }
        return null;
    }

    /**
     * Check availability for this room between two dates (inclusive)
     * Returns true if available (no overlapping bookings), false if occupied
     */
    public function isAvailableBetween($startDate, $endDate)
    {
        // Normalize dates
        $start = \Carbon\Carbon::parse($startDate)->toDateString();
        $end = \Carbon\Carbon::parse($endDate)->toDateString();

        // Find pengunjungs that have this room assigned (nomor_kamar stored as comma-separated values)
        // and have overlapping date ranges
                $overlapCount = \App\Models\Pengunjung::whereRaw("FIND_IN_SET(?, kode_kamar)", [$this->kode_kamar])
            ->where(function($q) use ($start, $end){
                                $q->whereBetween('check_in', [$start, $end])
                                    ->orWhereBetween('check_out', [$start, $end])
                  ->orWhere(function($q2) use ($start, $end){
                      $q2->where('check_in','<',$start)->where('check_out','>',$end);
                  });
            })->count();

        return $overlapCount === 0;
    }
}