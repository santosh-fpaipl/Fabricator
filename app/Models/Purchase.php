<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable =[
        'po_sid',
    ];

    public function getRouteKeyName()
    {
        return 'po_sid';
    }
   
}
