<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberHistoryPassword extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'member_history_password';
}
