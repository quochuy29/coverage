<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Scopes\DeleteScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Member extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;
    
    const DELETE_FLAG = 'member_is_deleted';
    const TABLE_MEMBER = 'members';

    protected $primaryKey = 'member_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'member_login_name',
        'member_name',
        'member_email',
        'member_phone_mobile',
        'member_password',
        'member_avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['member_password'];

    protected static function booted()
    {
        static::addGlobalScope(new DeleteScope());
    }

    public function setMemberPasswordAttribute($value)
    {
        $this->attributes['member_password'] = Hash::make($value);
    }

}
