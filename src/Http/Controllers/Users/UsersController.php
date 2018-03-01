<?php

namespace Runsite\CMF\Http\Controllers\Users;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\User\User;
use Runsite\CMF\Models\User\Group;
use Runsite\CMF\Models\User\UserGroup;

class UsersController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate();
        return view('runsite::users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        $groups = Group::orderBy('id', 'desc')->get();
        return view('runsite::users.create', compact('groups', 'user'));
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
            'is_locked' => '',
            'email' => 'required|email|max:255',
            'password' => 'required|string|confirmed:password_confirmation',
            'password_confirmation' => 'required',
        ]);

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        if($request->groups)
        {
            foreach($request->groups as $group_id)
            {
                $group = Group::findOrFail($group_id);
                $user->assignGroup($group);
            }
        }

        return redirect()->route('admin.users.index')->with('success', trans('runsite::users.User is created'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $groups = Group::orderBy('id', 'desc')->get();
        return view('runsite::users.edit', compact('groups', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'is_locked' => '',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|confirmed:password_confirmation',
            'password_confirmation' => 'nullable',
        ]);

        if($request->password)
        {
            $data['password'] = bcrypt($data['password']);
        }
        else
        {
            unset($data['password']);
        }

        $user->update($data);

        UserGroup::where('user_id', $user->id)->delete();

        if($request->groups)
        {
            foreach($request->groups as $group_id)
            {
                $group = Group::findOrFail($group_id);
                $user->assignGroup($group);
            }
        }

        return redirect()->route('admin.users.edit', $user)->with('success', trans('runsite::users.User is updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', trans('runsite::users.User is deleted'));
    }
}
