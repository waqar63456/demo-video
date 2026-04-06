<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\newusers;
use Illuminate\Support\Facades\Hash;

class NewusersController extends Controller
{
    public function create()
    {
        return view('admin.newusers.add');
    }
    public function store(Request $request)
    {
        // Validate all fields
        // dd($request->all());
        try {
            $validated = $request->validate([
                'name' => 'required|string',  // Name is required and must be a string with a max length of 255
                'email' => 'required|email|unique:newusers,email',  // Email is required, must be a valid email, and must be unique
                'phone' => 'required|string',  // Phone number is required and should be a string with a max length of 15
                'password' => 'required|string',  // Password is required, must be at least 8 characters, and must match the password_confirmation field

                'is_active' => 'nullable',  // Is_active is optional but should be a boolean
            ]);

            // Create the new user
            $newusers = new NewUsers();
            $newusers->name = $request->name;
            $newusers->email = $request->email;
            $newusers->phone = $request->phone;
            $newusers->user_type = 'user';
            $newusers->password = Hash::make($request->password);

            $newusers->is_active = $request->has('is_active') ? 1 : 0;
            $newusers->save();

            return redirect()->route('newusers.index')->with('success', 'New user added successfully.');
        } catch (\Exception $e) {
            // dd($e->getMessage());

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function index(Request $request)
    {
        $query = newusers::query();

        // Filter only sellers or earners
        // $query->where(function ($q) {
        //     $q->where('user_type', 'user')
        //         ->orWhere('user_type', 'earner');
        // });
        $query->whereIn('user_type', ['user' , 'seller', 'earner']);

        // Filter by user name (optional)
        if ($request->filled('name') && $request->name !== 'all') {
            $query->where('id', $request->name); // or use 'name' field if needed
        }

        // Clear filters if requested
        if ($request->has('clear_filter')) {
            return redirect()->route('newusers.index');
        }

        $newusers = $query->orderBy('created_at', 'desc')->paginate(15);
        $newusers->appends($request->query()); // Preserve filters in pagination links

        // For filter dropdown
        $allUsers = newusers::where('user_type', 'user')->get();

        return view('admin.newusers.view', compact('newusers', 'allUsers'));
    }
    public function destroy($id)
    {
        $newusers = newusers::findOrFail($id);
        $newusers->orderPackageProcesses()->delete();
        $newusers->userPackageProcesses()->delete();
        $newusers->userPaymentProcesses()->delete();


        $newusers->delete();

        return back()->with('success', 'newusers deleted successfully.');
    }
    public function edit($id)
    {
        $newusers = newusers::findOrFail($id);
        return view('admin.newusers.edit', compact('newusers'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Find the user to update
            $newusers = newusers::findOrFail($id);

            // Validate the input
            $validated = $request->validate([
                'name' => 'required|string',  // Name is required and must be a string
                'email' => 'required|email|unique:newusers,email,' . $newusers->id,  // Email must be unique but ignore the current user's email
                'phone' => 'required|string',  // Phone number is required and should be a string
                'password' => 'nullable|string',  // Password is optional, only required if provided

                'is_active' => 'nullable',  // Is_active is optional
            ]);

            // Update the user's details
            $newusers->name = $request->input('name');
            $newusers->email = $request->input('email');
            $newusers->phone = $request->input('phone');

            // Check if a new password is provided
            if ($request->filled('password')) {
                $newusers->password = bcrypt($request->input('password'));
            }


            $newusers->is_active = $request->input('is_active', 0); // ✅ Fix here


            // Save the updated user
            $newusers->save();

            // Redirect with a success message
            return redirect()->route('newusers.index')->with('success', 'New user updated successfully');
        } catch (\Exception $e) {
            // Catch any exception and display a specific error message
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function searchUsers(Request $request)
    {
        $search = $request->input('search');

        $users = newusers::where('user_type', 'user')->where('name', 'LIKE', "%{$search}%")
            ->select('id', 'name')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($users);
    }
}
