<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    
    function __construct()
    {
         $this->middleware('permission:permission-list|permission-create|permission-edit|permission-delete', ['only' => ['index','store']]);
         $this->middleware('permission:permission-create', ['only' => ['create','store']]);
         $this->middleware('permission:permission-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }


    public function index(Request $request)
    {
        $permissions = Permission::orderBy('id','DESC')->paginate(5);
        return view('permissions.index',compact('permissions'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }




    public function create()
    {
        $role =Role::get();

        return view('permissions.create',compact('role'));
    }



    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:permissions,name',
            'role' => 'required',
        ]);
    
        $permission = Permission::create(['name' => $request->input('name')]);
        $permission->syncRoles($request->input('role'));
    
        return redirect()->route('permissions.index')
                        ->with('success','Permission crée avec succès');
    }


    public function show($id)
    {
        $permission = Permission::find($id);
        $rolePermissions = Role::join("role_has_permissions","role_has_permissions.role_id","=","roles.id")
            ->where("role_has_permissions.permission_id",$id)
            ->get();
    
        return view('permissions.show',compact('permission','rolePermissions'));
    }


    public function edit($id)
    {
        $permission = Permission::find($id);
        $role = Role::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.permission_id",$id)
            ->pluck('role_has_permissions.role_id','role_has_permissions.role_id')
            ->all();
    
        return view('permissions.edit',compact('role','permission','rolePermissions'));
    }



    
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'role' => 'required',
        ]);
    
        $permission = Permission::find($id);
        $permission->name = $request->input('name');
        $permission->save();
    
        $permission->syncRoles($request->input('role'));
    
        return redirect()->route('permissions.index')
                        ->with('success','Mise a jour de la permission effectué avec succès');
    }



    public function destroy($id)
    {
        DB::table("permissions")->where('id',$id)->delete();
        return redirect()->route('permissions.index')
                        ->with('success','permission Supprimé avec succès');
    }


}
