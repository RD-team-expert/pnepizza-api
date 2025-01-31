<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

function makeDirectory($path)
{
    if (file_exists($path)) return true;
    return mkdir($path, 0755, true);
}

function removeFile($path)
{
    return file_exists($path) && is_file($path) ? @unlink($path) : false;
}

function generateSlug($string)
{
    $string = preg_replace('/[^a-z0-9\s\-]/i', '', $string);
    $string = preg_replace('/\s/', '-', $string);
    $string = preg_replace('/\-\-+/', '-', $string);
    $string = strtolower(trim($string, '-'));

    return $string;
}

function uploadImage(Request $request, string $location, string $name, $old = null)
{
    if ($request->hasFile($name)) {
        if (!empty($old)) {
            Storage::delete($location . '/' . $old);
        }
        $image = $request->file($name);
        $imageName = $image->hashName();
        $image->storeAs($location, $imageName);

        return $imageName;
    } else {
        return null;
    }
}

function getAllPaginate($model, string $search, int $show = 10)
{
    return $model->when(request()->q, function($data) use($search) {
        $data->where($search, 'LIKE', '%' . request()->q . '%');
    })->when(request()->sort, function($data) {
        $data->orderBy(request()->sort ?? 'created_at', request()->order ?? 'desc');
    })->paginate($show);
}
