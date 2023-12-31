<?php

namespace App\Http\Controllers\Advance\apply;
use App\Http\Controllers\Controller; // Import the Controller class

use Illuminate\Http\Request;
use App\Models\ExpenseType;
use App\Models\Policy;
use App\Models\Section;
use App\Models\User;
use App\Models\ExpenseApplication;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedMail;
use Illuminate\Support\Facades\Hash;
use App\Models\Advance;
use App\Models\SalaryAdvance;
use App\Models\DsaAdvance;
use App\Models\DsaSettlement;
use App\Models\DsaManualSettlement;
use App\Models\RateDefinition;
use App\Models\RateLimit;
use App\Models\Grade;
use App\Models\EnforcementOption;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;

class advance_apply  extends Controller
{
     //Get Advance Application form
     public function showAdvance()
     {
         $advance_type= Advance :: all();
         //dd($advance_type);
 
         return view('Advance.advance_apply.advanceloan_form', compact('advance_type'));
     }
     // request for advance loan for DSA_Advance and Salary_Advance
     public function addAdvanceLoan(Request $request)
     {
         // Get the authenticated user's ID
         $user_id = Auth::id();
 
         DB::beginTransaction();
          //dd($request->all()); 
         try {
 
             if ($request->input('advance_type') === 'dsa_advance') {
 
                 $advanceType = Advance::where('name', 'DSA Advance')->first();
 
                 $validatedData = $request->validate([
                     'advance_type' => 'required|in:dsa_advance',
                     'mode_of_travel' => 'required|string|max:255',
                     'from_location' => 'required|string|max:255',
                     'to_location' => 'required|string|max:255',
                     'from_date' => 'required|date',
                     'to_date' => 'required|date|after_or_equal:from_date',
                     'amount' => 'required|numeric|min:0',
                     'purpose' => 'required|string|max:255',
                     'upload_file' => 'nullable|file|mimes:pdf|max:2048', // Max size of 2 MB
                 ]);
 
                 \Log::info('Processing DSA Advance', ['data' => $validatedData]);
 
                 // Generate an advance number based on the current date and time
                 $currentDateTime = now();
                 $advanceNo = 'DSA' . $currentDateTime->format('YmdHis');
 
                 // Add the user_id and advance number to the validated data array
                 $validatedData['advance_type_id'] = $advanceType->id;
                 $validatedData['user_id'] = $user_id;
                 $validatedData['advance_no'] = $advanceNo;
 
                 DsaAdvance::create($validatedData);
 
             } elseif ($request->input('advance_type') === 'salary_advance') {
 
                 $advanceType = Advance::where('name', 'Salary Advance')->first();
 
                 $validatedData = $request->validate([
                     'advance_type' => 'required|in:salary_advance',
                     'emi_count' => 'required|integer|min:1',
                     'deduction_period' => 'required|date',
                     'amount' => 'required|numeric|min:0',
                     'purpose' => 'required|string|max:255',
                     'upload_file' => 'nullable|file|mimes:pdf|max:2048', // Max size of 2 MB
                 ]);
 
                 \Log::info('Processing Salary Advance', ['data' => $validatedData]);
 
                 // Generate an advance number based on the current date and time
                 $currentDateTime = now();
                 $advanceNo = 'SAL' . $currentDateTime->format('YmdHis');
 
                 // Add the user_id and advance number to the validated data array
                 $validatedData['advance_type_id'] = $advanceType->id;
                 $validatedData['user_id'] = $user_id;
                 $validatedData['advance_no'] = $advanceNo;
 
                 SalaryAdvance::create($validatedData);
             }
 
             DB::commit();
 
             return redirect()->route('show-advance-loan')
                 ->with('success', 'Advance added successfully');
 
         } catch (\Exception $e) {
             \Log::error('Error:', ['message' => $e->getMessage()]);
             DB::rollBack();
             //return redirect()->route('show-advance-loan')
             return back()->withInput()
             ->with('success', 'An error occurred while adding the advance: ' . $e->getMessage());        }
     }
 
 
 
}