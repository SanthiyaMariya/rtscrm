<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // This method handles the GET request (Displaying the homepage)
    public function index()
    {
        // Change 'welcome' to the name of your blade file if it's different
        return view('welcome'); 
    }

    // This method handles the POST request (When the form is submitted)
    public function store(Request $request)
    {
        // 1. Validate the incoming form data (Add your actual form field names here)
        // Example: 'name' => 'required|string|max:255'
        $validatedData = $request->validate([
            // 'your_input_name' => 'required',
        ]);

        // 2. Process the data (Save to database, send email, etc.)
        // ...

        // 3. Redirect back to the homepage with a success message
        return redirect('/')->with('success', 'Form submitted successfully!');
    }
}