<?php

namespace Runsite\CMF\Http\Controllers\Users;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\User\Invite;
use Runsite\CMF\Models\User\User;
use Runsite\CMF\Models\User\Group;
use Carbon\Carbon;

class InviteController extends BaseAdminController
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Invite $invite)
    {
        $groups = Group::orderBy('id', 'desc')->get();
        return view('runsite::users.invite.create', compact('invite', 'groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:rs_users',
            'expires_at' => 'required|date|max:255',
        ]);

        $data['token'] = md5(str_random(5) . time() . str_random(5));

        $user = User::create($data);

        $data['user_id'] = $user->id;

        $invite = Invite::create($data);

        if($request->groups)
        {
            foreach($request->groups as $group_id)
            {
                $group = Group::findOrFail($group_id);
                $user->assignGroup($group);
            }
        }

        return redirect()->route('admin.users.invite.show', $invite)->with('success', trans('runsite::users.invite.Invite is created'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Invite $invite)
    {
        return view('runsite::users.invite.show', compact('invite'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invite $invite)
    {
        $invite->delete();
        return redirect()->route('admin.users.index')->with('success', trans('runsite::users.invite.Invite is deleted'));
    }

    public function form($token)
    {
        $invite = Invite::where('token', $token)->first() or abort(404);

        if($invite->expires_at <= Carbon::now())
        {
            return view('runsite::auth.invite.expired');
        }

        return view('runsite::auth.invite.register', compact('invite'));
    }

    public function register(Request $request, $token)
    {
        $invite = Invite::where('token', $token)->first() or abort(404);

        if($invite->expires_at <= Carbon::now())
        {
            return view('runsite::auth.invite.expired');
        }

        $data = $request->validate([
            'password' => 'required|string|max:255|confirmed:password_confirmation',
            'password_confirmation' => 'required|string|max:255',
        ]);

        $invite->user->password = bcrypt($data['password']);
        $invite->user->save();
        $invite->delete();

        return redirect()->route('login')->with('success', trans('runsite::users.invite.Registration complete'));
    }
}
