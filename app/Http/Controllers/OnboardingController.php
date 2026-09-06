<?php

namespace App\Http\Controllers;

use App\Models\HeavyVehicle;
use App\Models\Location;
use App\Models\Operator;
use App\Models\Organization;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingController extends Controller
{
    public function index()
    {
        return redirect()->route('onboarding.organization');
    }

    public function organization(): Response
    {
        return Inertia::render('Onboarding/Organization');
    }

    public function storeOrganization(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'timezone' => 'required|string|max:255',
            'location_name' => 'nullable|string|max:255',
        ]);

        $org = Organization::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(6),
            'industry' => $validated['industry'],
            'timezone' => $validated['timezone'],
        ]);

        $user = Auth::user();
        $user->organization_id = $org->id;
        $user->save();

        if (!empty($validated['location_name'])) {
            $org->locations()->create([
                'name' => $validated['location_name'],
                'type' => 'mine',
                'organization_id' => $org->id,
            ]);
        }

        return redirect()->route('onboarding.vehicles');
    }

    public function vehicles(): Response
    {
        return Inertia::render('Onboarding/Vehicles');
    }

    public function storeVehicles(Request $request)
    {
        $validated = $request->validate([
            'vehicles' => 'required|array|min:1',
            'vehicles.*.model_name' => 'required|string|max:255',
            'vehicles.*.plate_number' => 'required|string|max:255',
            'vehicles.*.type' => 'required|string|in:Haul Truck,Excavator,Dozer,Passenger,Cargo',
            'vehicles.*.fuel_capacity_l' => 'nullable|numeric|min:0',
            'vehicles.*.location' => 'nullable|string|max:255',
        ]);

        $orgId = Auth::user()->organization_id;

        foreach ($validated['vehicles'] as $vehicleData) {
            $type = $vehicleData['type'];
            $isHeavy = in_array($type, ['Haul Truck', 'Excavator', 'Dozer']);

            if ($isHeavy) {
                $heavyVehicle = HeavyVehicle::create([
                    'vehicle_code' => $vehicleData['plate_number'],
                    'type' => $type,
                    'model' => $vehicleData['model_name'],
                    'status' => 'Idle',
                    'fuel_capacity_l' => $vehicleData['fuel_capacity_l'] ?? null,
                    'organization_id' => $orgId,
                ]);

                if (!empty($vehicleData['location'])) {
                    $location = Location::firstOrCreate(
                        ['name' => $vehicleData['location'], 'organization_id' => $orgId],
                        ['type' => 'mine']
                    );

                    Vehicle::create([
                        'model_name' => $vehicleData['model_name'],
                        'plate_number' => $vehicleData['plate_number'],
                        'type' => 'cargo',
                        'ownership' => 'owned',
                        'location_id' => $location->id,
                        'fuel_consumption' => 0,
                        'is_available' => true,
                        'distance_km' => 0,
                        'organization_id' => $orgId,
                    ]);
                }
            } else {
                $locationId = null;
                if (!empty($vehicleData['location'])) {
                    $location = Location::firstOrCreate(
                        ['name' => $vehicleData['location'], 'organization_id' => $orgId],
                        ['type' => 'mine']
                    );
                    $locationId = $location->id;
                }

                Vehicle::create([
                    'model_name' => $vehicleData['model_name'],
                    'plate_number' => $vehicleData['plate_number'],
                    'type' => strtolower($type),
                    'ownership' => 'owned',
                    'location_id' => $locationId,
                    'fuel_consumption' => 0,
                    'is_available' => true,
                    'distance_km' => 0,
                    'organization_id' => $orgId,
                ]);
            }
        }

        return redirect()->route('onboarding.operators');
    }

    public function operators(): Response
    {
        return Inertia::render('Onboarding/Operators');
    }

    public function storeOperators(Request $request)
    {
        $validated = $request->validate([
            'operators' => 'required|array|min:1',
            'operators.*.name' => 'required|string|max:255',
            'operators.*.license_number' => 'required|string|max:255',
            'operators.*.status' => 'required|string|in:On Shift,Off Duty,On Break',
        ]);

        $orgId = Auth::user()->organization_id;

        foreach ($validated['operators'] as $operatorData) {
            Operator::create([
                'name' => $operatorData['name'],
                'license_number' => $operatorData['license_number'],
                'status' => $operatorData['status'],
                'organization_id' => $orgId,
            ]);
        }

        return redirect()->route('onboarding.complete');
    }

    public function complete(): Response
    {
        return Inertia::render('Onboarding/Complete');
    }
}
