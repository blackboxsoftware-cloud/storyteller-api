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
        $status = request()->get('status');

        $serviceListingsQuery = ServiceListing::with(['service_provider.user'])
            ->whereHas('service_provider', function ($providerQuery) {
                $providerQuery->where('verified', true);
            });

        if ($status == 'approved') {
            $serviceListingsQuery->where('approved', true);
        }

        $serviceListings = $serviceListingsQuery->orderBy('updated_at', 'desc')->paginate($perPage);

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
            ->whereHas('service_provider', function ($providerQuery) {
                $providerQuery->where('verified', true);
            })
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


   public function approve(ServiceListing $serviceListing)
    {
        $user = auth()->user();

        if (!$user->admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized Action!'
            ], 403);
        }

        $serviceListing->approved = true;
        $serviceListing->comment = "";
        $serviceListing->save();

        return response()->json([
            'success' => true,
            'data' => $serviceListing
        ]);
    }

    public function reject(Request $request, ServiceListing $serviceListing)
    {
        $user = auth()->user();

        if (!$user->admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized Action!'
            ], 403);
        }

        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        $serviceListing->approved = false;
        $serviceListing->comment = $request->input('comment');
        $serviceListing->save();

        return response()->json([
            'success' => true,
            'data' => $serviceListing
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
        try {
            $validated = $request->validate([
                'title' => 'required|string',
                'description' => 'nullable|string',
                'price' => 'nullable|numeric',
                'image' => 'nullable|url',
            ]);

            $serviceListing = ServiceListing::findOrFail($id);

            // Optional: Ensure only owner or admin can update
            $user = auth()->user();
            if (!$user->admin && $serviceListing->service_provider->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized Action!'
                ], 403);
            }

            $serviceListing->title = $validated['title'];
            $serviceListing->description = $validated['description'] ?? $serviceListing->description;
            $serviceListing->price = $validated['price'] ?? $serviceListing->price;
            $serviceListing->image = $validated['image'] ?? $serviceListing->image;
            $serviceListing->approved = false;
            $serviceListing->comment = '';
            $serviceListing->save();

            return response()->json([
                'message' => 'Service Listing Updated Successfully',
                'data' => $serviceListing,
            ], 200);
        } catch (ValidationException $e) {
            Log::error('Update Service Listing validation error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Update Service Listing error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
