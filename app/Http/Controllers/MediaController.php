<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request)
    {

        $query  = Media::query();

        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $media = $query->paginate($perPage);

        return response()->json($media);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'file_name' => 'required|string',
            'file_path' => 'required|string',
            'thumbnail_path' => 'nullable|string',
            'file_size' => 'required|integer',
            'mime_type' => 'required|string',
        ]);

        $media = Media::create($request->all());
        return response()->json($media, 201);
    }

    public function show($id)
    {
        $media = Media::findOrFail($id);
        return response()->json($media);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'file_name' => 'required|string',
            'file_path' => 'required|string',
            'thumbnail_path' => 'nullable|string',
            'file_size' => 'required|integer',
            'mime_type' => 'required|string',
        ]);

        $media = Media::findOrFail($id);
        $media->update($request->all());
        return response()->json($media);
    }

    public function destroy($id)
    {
        $media = Media::findOrFail($id);
        $media->delete();
        return response()->json(null, 204);
    }
}
