<?php
 
namespace App\Http\Controllers;
 
use App\Models\Participant;
use Illuminate\Http\Request;
 
class ParticipanteController extends Controller
{
    public function index()
    {
        $participants = Participant::all();
        return view('participants.index', compact('participants'));
    }
 
    public function create()
    {
        return view('participants.create');
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'team_id' => 'required|exists:teams,id',
            'photo' => 'nullable|image|mimes:png|max:2048',
        ]);
 
        $participant = new Participant();
        $participant->name = $request->name;
        $participant->team_id = $request->team_id;
 
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $nombreFoto = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/participants'), $nombreFoto);
            $participant->photo = $nombreFoto;
        }
 
        $participant->save();
 
        return redirect()->route('participants.index')->with('success', 'Participante creado con éxito');
    }
 
    public function show(Participant $participant)
    {
        return view('participants.show', compact('participant'));
    }
 
    public function edit(Participant $participant)
    {
        return view('participants.edit', compact('participant'));
    }
 
    public function update(Request $request, Participant $participant)
    {
        $request->validate([
            'name' => 'required|max:255',
            'team_id' => 'required|exists:teams,id',
            'photo' => 'nullable|image|mimes:png|max:2048',
        ]);
 
        $data = $request->all();
 
        if ($request->hasFile('photo')) {
            if ($participant->photo && file_exists(public_path('assets/img/participants/' . $participant->photo))) {
                unlink(public_path('assets/img/participants/' . $participant->photo));
            }
 
            $file = $request->file('photo');
            $nombreFoto = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/participants'), $nombreFoto);
            $data['photo'] = $nombreFoto;
        }
 
        $participant->update($data);
 
        return redirect()->route('participants.index')->with('success', 'Participante actualizado con éxito');
    }
 
    public function destroy(Participant $participant)
    {
        if ($participant->photo && file_exists(public_path('assets/img/participants/' . $participant->photo))) {
            unlink(public_path('assets/img/participants/' . $participant->photo));
        }
 
        $participant->delete();
 
        return redirect()->route('participants.index')->with('success', 'Participante eliminado');
    }
}
