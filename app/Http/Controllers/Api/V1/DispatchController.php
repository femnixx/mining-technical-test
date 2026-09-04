<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreDispatchRequest;
use App\Models\ShiftDispatch;
use App\Models\HeavyVehicle;
use App\Models\Operator;
use Illuminate\Http\JsonResponse;

class DispatchController extends Controller
{
    public function __invoke(StoreDispatchRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $vehicle = HeavyVehicle::findOrFail($validated['vehicle_id']);
        $operator = Operator::findOrFail($validated['operator_id']);

        if ($operator->status === 'On Shift') {
            return response()->json([
                'success' => false,
                'message' => 'Operator is already on an active shift.',
            ], 422);
        }

        $dispatch = ShiftDispatch::create([
            'vehicle_id' => $vehicle->id,
            'operator_id' => $operator->id,
            'pit_location' => $validated['pit_location'],
            'shift_start' => now(),
            'target_tonnage' => $validated['target_tonnage'] ?? null,
        ]);

        $vehicle->update(['status' => 'Active']);
        $operator->update(['status' => 'On Shift']);

        return response()->json([
            'success' => true,
            'data' => $dispatch->load('vehicle', 'operator'),
        ], 201);
    }
}
