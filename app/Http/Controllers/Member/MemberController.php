<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::select('member_id', 'member_name', 'member_email', 'member_phone_mobile')
        ->orderBy($request->sortField ?? 'member_name', $request->sortType ?? 'asc');
        
        if ((string)$request->searchField !== '') {
            $query = $query->where('member_name', 'like', "%{$request->searchField}%");
        }

        return $query->paginate('10', ['*'], 'page', $request->page ?? 1);
    }

    public function getDetailMember($id)
    {
        return Member::select('member_avatar', 'member_name', 'member_phone_mobile', 'member_email', 'member_login_name')
        ->where('member_id', $id)
        ->first();
    }

    public function edit($id, Request $request)
    {
        $member = Member::findOrFail($id);
        $member->fill($request->member);
        $member->member_reset_pwd_is_required = 1;
        $member->save();

        return $member;
    }

    public function delete($id)
    {
        $member = Member::findOrFail($id);
        $member->member_is_deleted = 1;

        return $member->save();
    }
}
