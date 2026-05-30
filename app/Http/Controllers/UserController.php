<?php
 
namespace App\Http\Controllers;
 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
 
class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }
 
    public function create()
    {
        return view('users.create');
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'discipline_id' => 'nullable|exists:disciplines,id',
        ]);
 
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'discipline_id' => $request->discipline_id,
        ]);
 
        return redirect()->route('users.index')->with('success', 'Usuario creado con éxito');
    }
 
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
 
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }
 
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'discipline_id' => 'nullable|exists:disciplines,id',
        ]);
 
        $data = $request->except('password');
 
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
 
        $user->update($data);
 
        return redirect()->route('users.index')->with('success', 'Usuario actualizado con éxito');
    }
 
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado');
    }
}
