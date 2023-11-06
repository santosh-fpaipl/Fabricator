<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable =[
        'sid',
        'po_sid',
    ];

    public function getRouteKeyName()
    {
        return 'po_sid';
    }

    /*
        return something like this
        P-1023-0001
    */
    public static function generateId() {
        $static = 'P-';
        $month = date('m'); // Current month
        $year = date('y'); // Last two digits of the current year
        // Retrieve the last order by ID and get its ID
        $lastOrder = self::orderBy('id', 'desc')->first();
        $serial = $lastOrder ? $lastOrder->id + 1 : 1; // If there's no order, start from 1
        $serial = str_pad($serial, 4, '0', STR_PAD_LEFT); // Pad with zeros to make it at least 4 digits
        return $static . $month . $year . '-' . $serial;
    }    
   
}
