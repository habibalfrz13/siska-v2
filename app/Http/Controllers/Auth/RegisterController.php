<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Biodata;
use App\Models\User;
use App\Models\Profile; // Import model Profile
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        // Membuat pengguna baru
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'id_role' => 2,
            'password' => Hash::make($data['password']),
        ]);

        Biodata::create([
            'user_id' => $user->id,
        ]);

        return $user;
    }
}
