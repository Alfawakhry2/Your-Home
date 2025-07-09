<?php

namespace App\Http\Controllers\Api\filament;

use App\Models\Estate;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EstateResource;
use Illuminate\Support\Facades\Validator;

class EstateController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Estate::class, 'estate');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->role === 'admin') {
            $estates = Estate::with('reservations', 'images')->filter($request->query())->paginate(6);
        } else {
            $estates = $request->user()->estates;
        }
        return EstateResource::collection($estates);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'     => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'type'        => 'required|in:sale,rent',
            'bedrooms'    => 'nullable|integer',
            'bathrooms'   => 'nullable|integer',
            'area'        => 'required|numeric',
            'status'      => 'required|in:available,sold,rented',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'images.*'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // here we can add many images
            'location'    => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors'  => $validator->errors(),
                'message' => 'Validation errors'
            ], 422);
        }

        $data = $validator->validated();
        $data['slug'] = Str::slug($data['title']);

        //for main image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('estates', 'public');
            $data['image'] = $imagePath;
        }

        $estate = Estate::create($data);

        //for gallary , should after create as the observer creating
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('estates/gallery', 'public');
                $estate->images()->create([
                    'image' => $path,
                ]);
            }
        }

        return response()->json([
            'message' => 'Estate created successfully',
            'data'    => new EstateResource($estate)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $estate = Estate::find($id);
        if (!$estate) {
            return response()->json([
                'message' => 'Estate not found'
            ], 404);
        }

        return new EstateResource($estate);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $estate = Estate::with('images')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id'     => 'sometimes|required|exists:users,id',
            'category_id' => 'sometimes|required|exists:categories,id',
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'price'       => 'sometimes|required|numeric',
            'type'        => 'sometimes|required|in:sale,rent',
            'bedrooms'    => 'sometimes|required|integer',
            'bathrooms'   => 'sometimes|required|integer',
            'area'        => 'sometimes|required|numeric',
            'location'    => 'sometimes|required|string|max:255',
            'status'      => 'in:available,sold,rented',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'images.*'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }


        //validated will carry all validate data as array
        $validated = $validator->validated();

        $slug = $request->slug ?? Str::slug($validated['title']);

        $duplicate = Estate::where('slug', $slug)
            ->where('id', '!=', $estate->id)
            ->exists();

        if ($duplicate) {
            return response()->json(['errors' => ['slug' => ['This slug already exists.']]], 422);
        }
        $validated['slug'] = $slug;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('estates', 'public');
            $validated['image'] = $imagePath;
        }

        $estate->update($validated);

        if ($request->hasFile('images')) {
            $estate->images()->delete();

            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('estates/gallery', 'public');

                $estate->images()->create([
                    'image'    => $path,
                ]);
            }
        }

        $estate = Estate::with('images')->findOrFail($id);
        return response()->json([
            'message' => 'Estate updated successfully',
            'updated data' => new EstateResource($estate)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $estate = Estate::find($id);
        if (!$estate) {
            return response()->json([
                'status'  => false,
                'message' => 'Estate not found'
            ], 404);
        }

        // delete image
        if ($estate->image && file_exists(public_path('storage/' . $estate->image))) {
            unlink(public_path('storage/' . $estate->image));
        }

        $estate->delete();

        return response()->json([
            'status' => true,
            'message' => 'Estate deleted successfully'
        ]);
    }
}
