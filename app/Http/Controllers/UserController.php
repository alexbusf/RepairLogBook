<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = User::orderBy('id','DESC')->paginate(5);
        return view('users.index',compact('data'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();        
        return view('users.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
    
        $user = User::create($input);
        //$user->assignRole($request->input('roles'));
        
        // Получаем роли из запроса, исключая "Admin"
        $roles = $request->input('roles', []);
        $filteredRoles = array_filter($roles, function($role) {
            return $role !== 'Admin';
        });

        // Назначаем оставшиеся роли пользователю
        if (!empty($filteredRoles)) {
            $user->assignRole($filteredRoles);
        }
    
        return redirect()->route('users.index')->with('success','User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
    
        return view('users.edit',compact('user','roles','userRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }

        //закомметировать

        if (User::find($id)->getRoleNames()->contains('Admin'))
        {
            return redirect()->route('users.index')->with('success','Admin updated successfully');
            //return redirect()->route('users.index')->with('success','User role cannot be updates');
        }
    
        //раскомметировать
        // $user = User::find($id);
        // $user->update($input);
        // DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        // $user->assignRole($request->input('roles'));

        //закомметировать
        // Находим пользователя
        $user = User::find($id);

        // Обновляем данные пользователя
        $input = $request->all();
        $user->update($input);

        // Удаляем текущие роли пользователя
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        // Получаем роли из запроса, исключая "Admin"
        $roles = $request->input('roles', []);
        $filteredRoles = array_filter($roles, function($role) {
            return $role !== 'Admin';
        });

        // Назначаем оставшиеся роли пользователю
        if (!empty($filteredRoles)) {
            $user->assignRole($filteredRoles);
        }
    
        return redirect()->route('users.index')->with('success','User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (User::find($id)->getRoleNames()->contains('Admin'))
        {
            return redirect()->route('users.index')->with('success','User cannot be deleted');
        }
        
        User::find($id)->delete();
        return redirect()->route('users.index')->with('success','User deleted successfully');
    }
}
