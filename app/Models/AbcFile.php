<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbcFile extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * If your table name isn't the standard plural form of the model name,
     * uncomment and set the table name explicitly.
     *
     * @var string
     */
    // protected $table = 'abc_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mxl_path',
        'abc_filename',
        'title',
        'composer_name',
        'n_tracks',
        'score',
        'tracks_y',
        'original_key',
        'song_length_bars',
        'n_notes',
        'stored_abc_path', // We won't fill these during initial import
        // 'stored_mxl_path', // We won't fill these during initial import
    ];
}
