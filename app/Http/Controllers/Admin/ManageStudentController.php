<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class ManageStudentController extends Controller
{
    public function index()
    {
        $students = User::whereHas('role', function ($query) {
            $query->where('name', 'student');
        })->get();

        return view('admin.students.index', compact('students'));
    }

    public function edit(User $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, User $student)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'course' => 'required|string|max:255',
            'password' => 'nullable|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Prepare update data
        $updateData = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'course' => $request->course
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($student->avatar) {
                Storage::disk('public')->delete($student->avatar);
            }

            $avatarPath = $request->file('avatar')->store('student_avatars', 'public');
            $updateData['avatar'] = $avatarPath;
        }

        $student->update($updateData);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully');
    }

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'student_id' => 'required|unique:users,student_id',
            'course' => 'required|string|max:255',
            'password' => 'required|min:8|confirmed',
            'avatar' => 'nullable|image|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $studentData = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'student_id' => $request->student_id,
            'course' => $request->course,
            'password' => Hash::make($request->password),
            'role_id' => Role::where('name', 'student')->first()->id
        ];

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('student_avatars', 'public');
            $studentData['avatar'] = $avatarPath;
        }

        $student = User::create($studentData);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully');
    }

    public function show(User $student)
    {
        return view('admin.students.show', compact('student'));
    }

    public function destroy(User $student)
    {
        try {
            // Delete avatar if exists
            if ($student->avatar) {
                Storage::disk('public')->delete($student->avatar);
            }

            $student->delete();

            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting student: ' . $e->getMessage()
            ], 500);
        }
    }
}
