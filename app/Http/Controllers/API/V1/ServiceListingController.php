<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\ServiceListing;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class ServiceListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->get('per_page', 10);

        $serviceListings = ServiceListing::with(['service_provider.user'])
                    ->paginate($perPage);

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
        try{
            $validated = $request->validate([
                'title' => 'required|string',
                'description' => 'nullable|string',
                'price' => 'nullable|numeric',
                'service_provider_id' => 'required|string'
            ]);

            $service_listing = new ServiceListing();
            $service_listing->id = Str::uuid();
            $service_listing->title = $validated['title'];
            $service_listing->description = $validated['description'];
            $service_listing->price = $validated['price'];
            $service_listing->image = 'https://images.unsplash.com/photo-1605721911519-3dfeb3be25e7?q=80&w=800&auto=format&fit=crop';
            $service_listing->service_provider_id = $validated['service_provider_id'];
            $service_listing->save();

            return response()->json([
                'message' => 'Service Listing Created Successfully',
                'data' => $service_listing,
            ], 200);
        }catch (ValidationException $e) {
            Log::error('Create Service Listing error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Create Service Listing error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $perPage = $request->get('per_page', 10);

        $serviceListings = ServiceListing::with(['service_provider.user'])
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                ->orWhereHas('service_provider', function ($providerQuery) use ($query) {
                    $providerQuery->whereJsonContains('preferred_genres', $query)
                                    ->orWhereHas('user', function ($userQuery) use ($query) {
                                        $userQuery->where('first_name', 'like', "%{$query}%")
                                                ->orWhere('last_name', 'like', "%{$query}%")
                                                ->orWhere('email', 'like', "%{$query}%");
                                    });
                });
            })
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $serviceListings
        ]);
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
}
