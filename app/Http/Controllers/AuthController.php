<?php
namespace App\Http\Controllers;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
class AuthController extends Controller { public function create(): View { return view('admin.login'); } public function store(LoginRequest $request): RedirectResponse { if (! Auth::attempt(['email'=>$request->string('email'),'password'=>$request->string('password'),'is_admin'=>true],$request->boolean('remember'))) { return back()->withErrors(['email'=>'Email atau kata sandi tidak sesuai.'])->onlyInput('email'); } $request->session()->regenerate(); return redirect()->intended(route('admin.dashboard')); } public function destroy(): RedirectResponse { Auth::logout(); request()->session()->invalidate(); request()->session()->regenerateToken(); return redirect()->route('admin.login'); } }
