<?php

namespace App\Http\Controllers\Admin\RolePermission;

use App\Http\Controllers\Controller;
use App\Services\Admin\RolePermission\RoleService;
use App\DataTables\Admin\RolePermission\RoleDatatable;
use App\Http\Requests\Admin\RolePermission\RoleRequest;
use App\Services\Admin\RolePermission\PermissionService;

class RoleController extends Controller
{
    protected $permission_service = null;
    protected $role_service = null;

    public function __construct(PermissionService $permission_service, RoleService $role_service)
    {
        $this->middleware('permission:Role', ['only' => ['index']]);
        $this->middleware('permission:create_role', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_role', ['only' => ['edit', 'update']]);
        $this->middleware('permission:show_role', ['only' => ['show']]);
        $this->middleware('permission:delete_role', ['only' => ['destroy']]);

        $this->permission_service = $permission_service;
        $this->role_service = $role_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RoleDatatable $datatable)
    {
        return $datatable->render('admin.role-permission.role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $all_permissions = $this->permission_service->getAllParentPermissions(['children']);
        return view('admin.role-permission.role.create', compact('all_permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RoleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        try {
            $role = $this->role_service->updateOrCreateRole($request->all());

            return response()->json(['message' => 'Role created successfully', 'status' => 'success'], 200);
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
        $role = $this->role_service->get($id, ['permissions']);
        return view('admin.role-permission.role.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $all_permissions = $this->permission_service->getAllParentPermissions(['children']);
        $role = $this->role_service->get($id, ['permissions']);
        return view('admin.role-permission.role.edit', compact('all_permissions', 'role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RoleRequest  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        try {
            $role = $this->role_service->updateOrCreateRole($request->all(), $id);

            return response()->json(['message' => 'Role updated successfully', 'status' => 'success'], 200);
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
            $role = $this->role_service->deleteRole($id);
            return redirect()->back()->with('success', 'Role deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}