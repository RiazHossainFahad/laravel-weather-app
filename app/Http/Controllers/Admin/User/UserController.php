<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Services\Admin\User\UserService;
use App\DataTables\Admin\User\UserDatatable;
use App\Services\Admin\RolePermission\RoleService;

class UserController extends Controller
{
    protected $role_service = null;
    protected $user_service = null;

    public function __construct(RoleService $role_service, UserService $user_service)
    {
        $this->middleware('permission:User', ['only' => ['index']]);
        $this->middleware('permission:create_user', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_user', ['only' => ['edit', 'update']]);
        $this->middleware('permission:show_user', ['only' => ['show']]);
        $this->middleware('permission:delete_user', ['only' => ['destroy']]);

        $this->role_service = $role_service;
        $this->user_service = $user_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserDatatable $datatable)
    {
        return $datatable->render('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $all_roles = $this->role_service->get();
        return view('admin.user.create', compact('all_roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try {
            $user = $this->user_service->updateOrCreateUser($request->all());

            return response()->json(['message' => 'User created successfully', 'status' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'error'], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $all_roles = $this->role_service->get();
        $user = $this->user_service->get($id);
        $user->assigned_roles = $user->getRoleNames();
        return view('admin.user.edit', compact('user', 'all_roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserRequest  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        try {
            $user = $this->user_service->updateOrCreateUser($request->all(), $id);

            return response()->json(['message' => 'User updated successfully', 'status' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'error'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = $this->user_service->deleteUser($id);
            return redirect()->back()->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}