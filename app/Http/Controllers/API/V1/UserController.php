<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        try{
            $user = User::findOrFail($id);

            if ($user->service_provider()->exists()) {
                $user->load('service_provider.category');
            }

            if ($user->story_teller()->exists()) {
                $user->load('story_teller');
            }

            return response()->json([
                'message' => 'User fetched Successfully',
                'data' => new UserResource($user),
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Create Service Listing error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $validated = $request->validate([
                'first_name' => 'nullable|string',
                'last_name' => 'nullable|string',
                'description' => 'nullable|string',
                'website' => 'nullable|string',
                'location' => 'nullable|string',
                'availability' => 'nullable|string',
            ]);

            $user = User::findOrFail($id);

            $profile = $user->service_provider ?? $user->story_teller;

            $user->first_name = $validated['first_name'] ?? $user->first_name;
            $user->last_name = $validated['last_name'] ?? $user->last_name;
            $user->save();

            if ($profile) {
                if (array_key_exists('description', $validated)) {
                    $profile->description = $validated['description'];
                }

                if (array_key_exists('website', $validated)) {
                    $profile->website = $validated['website'];
                }

                if (array_key_exists('location', $validated)) {
                    $profile->location = $validated['location'];
                }

                if (array_key_exists('availability', $validated)) {
                    $profile->availability = $validated['availability'];
                }

                $profile->save();
            }


            if ($user->service_provider()->exists()) {
                $user->load('service_provider.category');
            }

            if ($user->story_teller()->exists()) {
                $user->load('story_teller');
            }

            return response()->json([
                'message' => 'User updated Successfully',
                'data' => new UserResource($user),
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
