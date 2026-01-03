<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Kelas API Resource
 * 
 * Transforms Kelas model for API responses
 */
class KelasResource extends JsonResource
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
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'harga' => $this->harga,
            'harga_formatted' => 'Rp ' . number_format($this->harga, 0, ',', '.'),
            'kuota' => $this->kuota,
            'kuota_tersisa' => $this->kuota - ($this->myClasses ? $this->myClasses->count() : 0),
            'pelaksanaan' => $this->pelaksanaan,
            'pelaksanaan_formatted' => \Carbon\Carbon::parse($this->pelaksanaan)->format('d M Y'),
            'status' => $this->status,
            'status_badge' => $this->status === 'Aktif' ? 'success' : 'danger',
            'foto' => $this->foto,
            'foto_url' => $this->foto ? url('images/galerikelas/' . $this->foto) : null,
            'kategori' => $this->whenLoaded('kategori', function () {
                return [
                    'id' => $this->kategori->id,
                    'nama' => $this->kategori->nama,
                ];
            }),
            'vendor' => $this->whenLoaded('vendor', function () {
                return [
                    'id' => $this->vendor->id,
                    'nama' => $this->vendor->nama,
                ];
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
