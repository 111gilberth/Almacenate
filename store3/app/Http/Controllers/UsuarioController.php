<?php

namespace SisNacho\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use DB;
use Spatie\Permission\Models\Role;
use SisNacho\User;
use Yajra\DataTables\Facades\DataTables;

class UsuarioController extends Controller
{
    public function __construct()
    {
      parent::__construct();
    }

    public function index()
    {
        $user = User::where('estado','Activo')->get();
        $roles = Role::all();

        return view('usuarios.index', compact('user', 'roles'));

    }

    public function tabla()
    {
        $users = User::where('estado','Activo')->get();


        return Datatables::of($users)
            ->addColumn('opcion', function ($ar) {
                return '
                        <div class="btn-group">
                          <a href="#" data-toggle="modal" data-target="#modal-borrar-' . $ar->id . '" class="btn btn-xs btn-danger"><i data-toggle="tooltip" title="Borrar usuario: '.$ar->name.'"  class="fa fa-trash"></i></a>
                          <a href="#" data-toggle="modal" data-target="#modal-editar-' . $ar->id . '" class="btn btn-xs btn-info"><i data-toggle="tooltip" title="Editar usuario: '.$ar->name.'"  class="fa fa-edit"></i></a>
                        </div>
                ';
            })
            ->editColumn('name', function ($us) {
                return $us->name.' '.$us->apellido;
            })
            ->editColumn('rol', function ($us) {
                return $us->roles[0]->name;
            })
            ->rawColumns(['opcion'])
            ->make(true);
    }

    public function store (Request $request)
    {

        $user = New User();
        $user->name = $request->name;
        $user->apellido = $request->apellido;
        $user->estado = 'Activo';
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $user->assignRole($request->rol);

        toastr()->success('Su usuario se ha agregado correctamente!', ''.$request->name);
        return Redirect::back();
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password != null)
        {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $user->syncRoles($request->rol);

        toastr()->info('Su usuario se ha editado correctamente!', ''.$request->name);
        return Redirect::back();
    }

    public function delete($id)
    {
        $users = User::find($id);
        $users->estado = 'Desactivo';
        $users->save();
        toastr()->error('Su usuario se ha borrado correctamente!', ''.$users->name);
        return Redirect::back();

    }
}
