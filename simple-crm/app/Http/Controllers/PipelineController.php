<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Pipeline;
use App\Models\PipelineStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PipelineController extends Controller
{
    public function index()
    {
        // Get any pipeline (active or not) first
        $pipeline = Pipeline::with(['stages.deals' => function ($q) {
            $q->orderBy('position')->orderBy('created_at');
        }])->orderBy('order')->first();

        // Bootstrap a default pipeline/stages if none exist
        if (!$pipeline) {
            $pipeline = Pipeline::create([
                'name' => 'Default Pipeline',
                'description' => 'Standard sales pipeline',
                'is_active' => true,
                'order' => 0,
                'created_by' => auth()->id() ?? 1,
            ]);
        }

        if ($pipeline && $pipeline->stages()->count() === 0) {
            $defaults = [
                ['name' => 'Qualified', 'probability' => 10, 'order' => 1, 'color' => '#64748b'],
                ['name' => 'Contact Made', 'probability' => 20, 'order' => 2, 'color' => '#22c55e'],
                ['name' => 'Demo Scheduled', 'probability' => 40, 'order' => 3, 'color' => '#eab308'],
                ['name' => 'Proposal Made', 'probability' => 60, 'order' => 4, 'color' => '#8b5cf6'],
                ['name' => 'Negotiations Started', 'probability' => 75, 'order' => 5, 'color' => '#f97316'],
                ['name' => 'Deal Closed', 'probability' => 100, 'order' => 6, 'color' => '#10b981'],
            ];
            foreach ($defaults as $s) {
                $pipeline->stages()->create(array_merge($s, ['is_active' => true]));
            }
            $pipeline->load('stages');
        }

        return view('pipeline.index', [
            'pipeline' => $pipeline,
            'stages' => $pipeline->stages,
        ]);
    }

    public function moveDeal(Request $request)
    {
        $validated = $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'to_stage_id' => 'required|exists:pipeline_stages,id',
            'to_position' => 'nullable|integer|min:0',
        ]);

        $deal = Deal::findOrFail($validated['deal_id']);
        $toStage = PipelineStage::findOrFail($validated['to_stage_id']);

        DB::transaction(function () use ($deal, $toStage, $validated) {
            // If moving within same stage and position provided, reorder others
            $newPosition = (int) ($validated['to_position'] ?? 0);

            // Shift positions in target stage
            Deal::where('pipeline_stage_id', $toStage->id)
                ->where('id', '!=', $deal->id)
                ->where('position', '>=', $newPosition)
                ->increment('position');

            $deal->pipeline_id = $toStage->pipeline_id;
            $deal->pipeline_stage_id = $toStage->id;
            $deal->position = $newPosition;
            $deal->save();
        });

        return response()->json(['status' => 'ok']);
    }
}


