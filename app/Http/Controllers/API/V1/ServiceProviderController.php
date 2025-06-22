<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\ServiceListing;
use App\Models\ServiceProvider;
use App\Models\User;
use Illuminate\Http\Request;

class ServiceProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->get('per_page', 10);

        $users = User::whereHas('service_provider')
            ->with(['service_provider.category'])
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function serviceListings(ServiceProvider $serviceProvider)
    {
        $serviceListings = $serviceProvider->listings;

        return response()->json([
            'success' => true,
            'data' => $serviceListings
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceProvider $serviceProvider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceProvider $serviceProvider)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceProvider $serviceProvider)
    {
        //
    }
}
