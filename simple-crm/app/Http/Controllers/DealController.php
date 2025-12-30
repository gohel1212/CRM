<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Customer;
use App\Models\Contact;
use App\Models\Pipeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DealController extends Controller
{
    public function index()
    {
        $deals = Deal::with(['customer', 'contact', 'owner'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('deals.index', compact('deals'));
    }

    public function create()
    {
        $customers = Customer::all();

        return view('deals.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'expected_close_date' => 'nullable|date',
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        $deal = new Deal($validated);
        $deal->status = 'open';
        $deal->owner_id = Auth::id();

        // Assign to first active pipeline stage
        $pipeline = Pipeline::with('stages')->active()->orderBy('order')->first();

        if ($pipeline && $pipeline->stages->count()) {
            $firstStage = $pipeline->stages->sortBy('order')->first();
            $deal->pipeline_id = $pipeline->id;
            $deal->pipeline_stage_id = $firstStage->id;
            $deal->position = (int) Deal::where('pipeline_stage_id', $firstStage->id)->max('position') + 1;
        }

        $deal->save();

        return redirect()->route('deals.index')
            ->with('success', 'Deal created successfully.');
    }

    public function show(Deal $deal)
    {
        $deal->load(['customer', 'contact', 'owner']);
        return view('deals.show', compact('deal'));
    }

    public function edit(Deal $deal)
    {
        $customers = Customer::all();
        $pipelines = Pipeline::all();

        return view('deals.edit', compact('deal', 'customers', 'pipelines'));
    }

    public function update(Request $request, Deal $deal)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'expected_close_date' => 'nullable|date',
            'pipeline_id' => 'nullable|exists:pipelines,id',
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        $deal->update($validated);

        return redirect()->route('deals.index')
            ->with('success', 'Deal updated successfully.');
    }

    public function destroy(Deal $deal)
    {
        $deal->delete();

        return redirect()->route('deals.index')
            ->with('success', 'Deal deleted successfully.');
    }

    public function apiIndex()
    {
        $deals = Deal::with(['customer', 'contact', 'owner'])->get();
        return response()->json($deals);
    }
}