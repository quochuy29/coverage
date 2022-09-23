<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        return Member::select('member_name', 'member_email', 'member_phone_mobile')
        ->orderBy($request->sortField ?? 'member_name', $request->sortType ?? 'asc')
        ->get();
    }
}
