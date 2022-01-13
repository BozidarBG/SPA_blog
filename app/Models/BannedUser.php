<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class BannedUser extends Model
{
    use HasFactory, softDeletes;

    protected $hidden=['deleted_at', 'updated_at'];

    public function banned_user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function banned_by(){
        return $this->belongsTo(User::class, 'banned_by');
    }
}
