<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transaction_type',
        'transaction_id',
        'file_path'
    ];
}
