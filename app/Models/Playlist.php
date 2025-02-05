<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model {
    use HasFactory;

    protected $appends = ['statusClass', 'statusText'];
    protected $dates = ['checked_at'];
    protected $guarded = [];

    public function getStatusClassAttribute() {
        $classes = [
            'not_processed' => 'table-warning',
            'queued' => 'table-light',
            'getting_info' => 'table-info',
            'downloading' => 'table-primary',
            'error' => 'table-danger',
        ];

        return $classes[$this->status] ?? null;
    }

    public function getStatusTextAttribute() {
        $replace = [
            'not_processed' => 'Not Processed',
            'queued' => 'Queued',
            'getting_info' => 'Getting Info...',
            'processed' => 'Processed',
            'error' => 'Error',
        ];

        return str_replace(array_keys($replace), array_values($replace), $this->status);
    }

    public function items() {
        return $this->hasMany(Item::class);
    }

}
