<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Fpaipl\Panel\Traits\CascadeSoftDeletes;
use Fpaipl\Panel\Traits\CascadeSoftDeletesRestore;
use Fpaipl\Panel\Traits\ManageModel;
use Fpaipl\Panel\Traits\Authx;
use Fpaipl\Panel\Traits\ManageTag;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\User;
use App\Models\Address;
use App\Traits\HasActive;

class Fabricator extends Model
{
    use
        Authx,
        HasFactory,
        SoftDeletes,
        CascadeSoftDeletes,
        CascadeSoftDeletesRestore,
        LogsActivity,
        ManageModel,
        ManageTag,
        HasActive;

    // Properties

    /*
        Auto Generated Columns:
        id
    */

    protected $fillable = [
        'user_id',
        'sid',
        'description',
        'tags'
    ];

    public function getRouteKeyName()
    {
        return 'sid';
    }

   
    protected $cascadeDeletes = [];

    protected $CascadeSoftDeletesRestore = [];

    protected $dependency = [];

    // public static function INDEXABLE(){
    //     return false;
    // }

    public function hasDependency()
    {
        return count($this->dependency);
    }

    public function getDependency()
    {
        return $this->dependency;
    }
    
   
    

    // Helper Functions

      

    // Relationships

    public function user(){

        return $this->belongsTo(User::class);
    }

    
    public function addresses(){

        return $this->hasMany(Address::class);

    }

   
   
    //End of Relationships

    // Logging

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id', 
                'user_id',
                'sid',
                'description',
                'tags',
                'created_at', 
                'updated_at', 
                'deleted_at'
            ])
            ->useLogName('model_log');
    }
}

