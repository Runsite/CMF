<?php

namespace Runsite\CMF\Http\Controllers\Users;

use Illuminate\Http\Request;
use Runsite\CMF\Http\Controllers\BaseAdminController;
use Runsite\CMF\Models\User\Group;

class GroupsController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::orderBy('id', 'desc')->paginate();
        return view('runsite::groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Group $group)
    {
        return view('runsite::groups.create', compact('group'));
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
            'description' => '',
        ]);

        Group::create($data);

        return redirect()->route('admin.groups.index')->with('success', trans('runsite::groups.Group is created'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        return view('runsite::groups.edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => '',
        ]);

        $group->update($data);

        return redirect()->route('admin.groups.edit', $group)->with('success', trans('runsite::groups.Group is updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('admin.groups.index')->with('success', trans('runsite::groups.Group is deleted'));
    }
}
