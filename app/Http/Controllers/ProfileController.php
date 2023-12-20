<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\{
    User
};

class ProfileController extends Controller
{
    //
    public function index(){
        return view('profile.index');
    }
    public function updateProfile(Request $request){
        $request->validate([
            'nama' => 'required',
            'email' => 'required'
        ]);

        $user = User::find(Auth::id());
        $user->name = $request->nama;
        $user->email = $request->email;
        $user->save();

        return redirect('/admin/profile')->with('success', 'Berhasil Mengganti Data Profile!');
    }
    public function changePicture(){
        return view('profile.change-picture');
    }
    public function updatePicture(Request $request){
        $request->validate([
            'profile' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $file = $request->file('profile');
        $originalName1 = $file->getClientOriginalName();
        $berkas = time().Auth::user()->name.$originalName1;
        $file->move('uploads/profile', $berkas);

        $user = User::find(Auth::id());
        // Cek Apakah Ada Foto sebelumnya
        if($user->profile_picture != null || !empty($user->profile_picture) || $user->profile_picture != ''){
            File::delete('uploads/profile/' . $user->profile_picture);
        }
        $user->profile_picture = $berkas;
        $user->save();

        return redirect('/admin/profile')->with('success', 'Berhasil Mengganti Foto Profile!');
    }
    public function changeSignature(){
        return view('profile.change-signature');
    }
    public function updateSignature(Request $request){
        $request->validate([
            'signature' => 'required|image|mimes:png|max:2048',
        ]);
        $file = $request->file('signature');
        $berkas = time().Auth::user()->id.'signature.png';
        $file->move('uploads/signature', $berkas);

        $user = User::find(Auth::id());
        // Cek Apakah Ada Foto sebelumnya
        if($user->signature != null || !empty($user->signature) || $user->signature != ''){
            File::delete('uploads/signature/' . $user->signature);
        }
        $user->signature = $berkas;
        $user->save();

        return redirect('/admin/profile')->with('success', 'Berhasil Mengganti Tanda Tangan!');
    }
    public function changePassword(){
        return view('profile.change-password');
    }
    public function updatePassword(Request $request){
        
        if(password_verify($request->old_password, Auth::user()->password)){
            $request->validate([
                'password' => ['required', 'confirmed', Password::min(8)->numbers()],
            ]);
            $user = User::find(Auth::id());
            $user->password = bcrypt($request->password);
            $user->save();

            return redirect('/admin/profile')->with('success', 'Berhasil Mengganti Password!');
        }else{
            return redirect()->back()->with('error', 'Current Password Salah!');
        }
    }
}
