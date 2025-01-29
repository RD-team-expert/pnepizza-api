<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    // GET /api/jobs (Read all jobs)
    public function index(Request $request)
    {
        $query = Job::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by job type
        if ($request->has('job_type')) {
            $query->where('job_type', $request->input('job_type'));
        }

        // Filter by city
        if ($request->has('city')) {
            $query->where('city', $request->input('city'));
        }

        // Full-text search on job_title or job_description
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('job_title', 'like', "%$search%")
                    ->orWhere('job_description', 'like', "%$search%");
            });
        }

        $jobs = $query->get();
        return response()->json($jobs);
    }

    // POST /api/jobs (Create a new job)
    public function store(Request $request)
    {
        $request->validate([
            'job_title' => 'required|string|max:255',
            'min_salary' => 'required|numeric',
            'max_salary' => 'required|numeric',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'job_type' => 'required|string|max:255',
            'job_description' => 'required|string',
            'indeed_link' => 'nullable|url',
            'workstream_link' => 'nullable|url',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        $job = Job::create([
            'job_title' => $request->input('job_title'),
            'min_salary' => $request->input('min_salary'),
            'max_salary' => $request->input('max_salary'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'job_type' => $request->input('job_type'),
            'job_description' => $request->input('job_description'),
            'indeed_link' => $request->input('indeed_link'),
            'workstream_link' => $request->input('workstream_link'),
            'status' => $request->input('status', 'active'), // Default to 'active'
        ]);

        return response()->json($job, 201);
    }

    // GET /api/jobs/{id} (Read a single job)
    public function show($id)
    {
        $job = Job::findOrFail($id);
        return response()->json($job);
    }

    // PUT /api/jobs/{id} (Update a job)
    public function update(Request $request, $id)
    {
        $job = Job::findOrFail($id);

        $request->validate([
            'job_title' => 'sometimes|string|max:255',
            'min_salary' => 'sometimes|numeric',
            'max_salary' => 'sometimes|numeric',
            'city' => 'sometimes|string|max:255',
            'state' => 'sometimes|string|max:255',
            'job_type' => 'sometimes|string|max:255',
            'job_description' => 'sometimes|string',
            'indeed_link' => 'nullable|url',
            'workstream_link' => 'nullable|url',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        $job->update($request->all());
        return response()->json($job);
    }

    // DELETE /api/jobs/{id} (Delete a job)
    public function destroy($id)
    {
        $job = Job::findOrFail($id);
        $job->delete();
        return response()->json(null, 204);
    }
}
