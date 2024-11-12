<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtractedData extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'nik', 'pdf_file_path'];
}
