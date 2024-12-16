<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Password;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('student_id', 'password');

        // Validate input
        $validator = Validator::make($credentials, [
            'student_id' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('student_id'));
        }

        // Attempt login
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Redirect based on role
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('student.dashboard');
        }

        return redirect()->back()
            ->withErrors(['login' => 'Invalid credentials'])
            ->withInput($request->only('student_id'));
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => [
                'required',
                'unique:users,student_id',
            ],
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.[a-z])(?=.[A-Z])(?=.\d)(?=.[@$!%?&])[A-Za-z\d@$!%?&]{8,}$/'
            ],
            'course' => 'required|string|max:255',
        ], [
            'password.regex' => 'Password must include uppercase, lowercase, and number',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        // Create user
        $studentRole = Role::where('name', 'student')->first();
        $user = User::create([
            'student_id' => $request->student_id,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $studentRole->id,
            'course' => $request->course
        ]);

        // Trigger registered event for email verification
        event(new Registered($user));

        // Log the user in
        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    // Combine email verification methods into this single controller
    public function notice()
    {
        return view('auth.verify-email');
    }

    public function verify(Request $request)
{
    $user = User::findOrFail($request->route('id'));

    if (!hash_equals(
        (string) $request->route('hash'),
        sha1($user->getEmailForVerification())
    )) {
        abort(403, 'Invalid verification link');
    }

    if ($user->hasVerifiedEmail()) {
        return redirect()->route('student.dashboard');
    }

    $user->markEmailAsVerified();

    return redirect()->route('student.dashboard')
        ->with('status', 'Your email has been verified successfully!');
}

    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('student.dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

    // Email Verification Methods
    public function verificationNotice()
    {
        return view('auth.verify-email');
    }

    public function sendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('student.dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->route('student.dashboard')
            ->with('status', 'Email verified successfully!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
}
