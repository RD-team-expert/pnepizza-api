<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;


class JobController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/jobs",
     *     summary="Get all jobs",
     *     tags={"Jobs"},
     *
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter jobs by status (active, inactive)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="job_type",
     *         in="query",
     *         description="Filter jobs by job type",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="city",
     *         in="query",
     *         description="Filter jobs by city",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by job title or job description",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of jobs",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Job"))
     *     ),
     *     @OA\Header(
     *         header="Accept",
     *         description="application/json only",
     *         @OA\Schema(type="string", example="application/json")
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {

        $query = Job::query();

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('job_type')) {
            $query->where('job_type', $request->input('job_type'));
        }

        if ($request->has('city')) {
            $query->where('city', $request->input('city'));
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('job_title', 'like', "%$search%")
                    ->orWhere('job_description', 'like', "%$search%");
            });
        }

        return response()->json($query->get());

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/jobs",
     *     summary="Create a new job posting",
     *     tags={"Jobs"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Job")
     *     ),
     *     @OA\Response(response=201, description="Job created successfully"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Header(
     *         header="Accept",
     *         description="application/json only",
     *         @OA\Schema(type="string", example="application/json")
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {


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

        $job = Job::create($request->all());
        return response()->json($job, 201);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/jobs/{id}",
     *     summary="Get a single job posting",
     *     tags={"Jobs"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Job ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Job details"),
     *     @OA\Response(response=404, description="Job not found"),
     *     @OA\Header(
     *         header="Accept",
     *         description="application/json only",
     *         @OA\Schema(type="string", example="application/json")
     *     )
     * )
     */
    public function show($id)
    {
        try {

        return response()->json(Job::findOrFail($id));

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/jobs/{id}",
     *     summary="Update a job posting",
     *     tags={"Jobs"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Job ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Job")
     *     ),
     *     @OA\Response(response=200, description="Job updated"),
     *     @OA\Response(response=404, description="Job not found"),
     *     @OA\Header(
     *         header="Accept",
     *         description="application/json only",
     *         @OA\Schema(type="string", example="application/json")
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
        $job = Job::findOrFail($id);
        $job->update($request->all());
        return response()->json($job);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/jobs/{id}",
     *     summary="Delete a job posting",
     *     tags={"Jobs"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Job ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Job deleted"),
     *     @OA\Response(response=404, description="Job not found"),
     *     @OA\Header(
     *         header="Accept",
     *         description="application/json only",
     *         @OA\Schema(type="string", example="application/json")
     *     )
     * )
     */
    public function destroy($id)
    {
        try {

        Job::findOrFail($id)->delete();
        return response()->json(null, 204);

        } catch (\Exception $exception) {
            return response()->json([
                'msg' => $exception->getMessage(),
            ]);
        }
    }
}
