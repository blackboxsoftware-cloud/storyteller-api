<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\ServiceListing;
use App\Models\ServiceProvider;
use App\Models\StoryTeller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $storyTellerCount = User::whereHas('story_teller')->count();
        $serviceProviderCount = User::whereHas('service_provider')->count();
        $blogPostCount = BlogPost::count();
        $serviceListingApprovedCount = ServiceListing::where('approved', true)->count();
        $serviceListingCount = ServiceListing::count();

        return response()->json([
            'message' => true,
            'data' => [
                    'storyTellerCount' => $storyTellerCount,
                    'serviceProviderCount' => $serviceProviderCount,
                    'blogPostCount' => $blogPostCount,
                    'serviceListingApprovedCount' => $serviceListingApprovedCount,
                    'serviceListingCount' => $serviceListingCount
                ]
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
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Update the verified status of a service provider.
     */
    public function updateProviderStatus(Request $request, $id)
    {
        $request->validate([
            'verified' => 'required|boolean',
        ]);

        $serviceProvider = ServiceProvider::find($id);

        if (!$serviceProvider) {
            return response()->json([
                'message' => 'Service provider not found'
            ], 404);
        }

        $serviceProvider->verified = $request->verified;
        $serviceProvider->save();

        return response()->json([
            'message' => 'Service provider verified status updated successfully',
            'data' => $serviceProvider
        ]);
    }
}
