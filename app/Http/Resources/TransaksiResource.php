<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transaksi API Resource
 * 
 * Transforms Transaksi model for API responses
 */
class TransaksiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'jumlah_pembayaran' => $this->jumlah_pembayaran,
            'jumlah_formatted' => 'Rp ' . number_format($this->jumlah_pembayaran, 0, ',', '.'),
            'status_pembayaran' => $this->status_pembayaran,
            'status_badge' => $this->getStatusBadge(),
            'tanggal_transaksi' => $this->tanggal_transaksi,
            'tanggal_formatted' => $this->tanggal_transaksi 
                ? \Carbon\Carbon::parse($this->tanggal_transaksi)->format('d M Y H:i') 
                : null,
            'snap_token' => $this->snap_token,
            'kelas' => $this->whenLoaded('kelas', function () {
                return [
                    'id' => $this->kelas->id,
                    'judul' => $this->kelas->judul,
                    'harga' => $this->kelas->harga,
                    'foto_url' => $this->kelas->foto 
                        ? url('images/galerikelas/' . $this->kelas->foto) 
                        : null,
                ];
            }),
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                    'biodata' => $this->user->biodata ? [
                        'no_hp' => $this->user->biodata->no_hp,
                        'alamat' => $this->user->biodata->alamat,
                    ] : null,
                ];
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get status badge color
     */
    private function getStatusBadge(): string
    {
        return match (strtolower($this->status_pembayaran)) {
            'berhasil' => 'success',
            'pending' => 'warning',
            'gagal' => 'danger',
            default => 'secondary',
        };
    }
}
