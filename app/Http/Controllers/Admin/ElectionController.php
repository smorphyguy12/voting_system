<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ElectionController extends Controller
{

    public function index()
    {
        $elections = Election::all();
        return view('admin.elections.index', compact('elections'));
    }

    public function create()
    {
        return view('admin.elections.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:elections,name|max:255',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date'
        ], [
            'name.unique' => 'An election with this name already exists.',
            'end_date.after' => 'End date must be after the start date.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $election = Election::create([
            'name' => $request->input('name'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'status' => 'upcoming'
        ]);

        return redirect()->route('admin.elections.index')
            ->with('success', 'Election created successfully');
    }

    public function show(Election $election)
    {
        // Detailed election analytics
        $candidates = $election->candidates()->with('user')->get();
        $results = $election->calculateResults();
        
        // Prepare data for charts
        $chartData = [
            'labels' => $results->pluck('candidate'),
            'votes' => $results->pluck('votes'),
            'percentages' => $results->pluck('percentage')
        ];

        return view('admin.elections.show', [
            'election' => $election,
            'candidates' => $candidates,
            'results' => $results,
            'chartData' => $chartData
        ]);
    }

    public function edit(Election $election)
    {
        return view('admin.elections.edit', compact('election'));
    }

    public function update(Request $request, Election $election)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:elections,name,' . $election->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'in:upcoming,active,closed'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $election->update($request->only(['name', 'start_date', 'end_date', 'status']));

        return redirect()->route('admin.elections.index')
            ->with('success', 'Election updated successfully');
    }

    public function destroy(Election $election)
    {
        try {
            DB::transaction(function () use ($election) {
                // Remove associated candidates and votes
                $election->candidates()->delete();
                $election->votes()->delete();
                $election->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Election deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting election: ' . $e->getMessage()
            ], 500);
        }
    }

    // Additional method for generating election report
    public function generateReport(Election $election)
    {
        $results = $election->calculateResults();

        $pdf = Pdf::loadView('reports.election_results', [
            'election' => $election,
            'results' => $results
        ]);

        return $pdf->download("election_results_{$election->name}.pdf");
    }
}