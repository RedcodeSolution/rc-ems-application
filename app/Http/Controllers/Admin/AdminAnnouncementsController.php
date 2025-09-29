<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AdminAnnouncementsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::orderBy('created_at', 'desc')->paginate(5);

        // Count totals
        $totalAnnouncements = Announcement::count();
        $publishedCount     = Announcement::where('status', 'published')->count();
        $scheduledCount     = Announcement::where('status', 'scheduled')->count();

        return view('admin.announcements.index', compact(
            'announcements',
            'totalAnnouncements',
            'publishedCount',
            'scheduledCount',
        ));
    }


    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'priority'          => 'required|in:low,medium,high,urgent',
            'category'          => 'required|string|max:50',
            'content'           => 'required|string',
            'expires_at'        => 'nullable|date',
            'target_audience'   => 'nullable|array',
            'target_audience.*' => 'in:all,managers,department_heads',
            'department_id'     => 'nullable|exists:departments,department_id'
        ]);

        $status = 'published';
        if (!empty($validated['expires_at'])) {
            $status = now()->lt($validated['expires_at']) ? 'scheduled' : 'published';
        }

        $announcement = Announcement::create($validated + [
            'target_audience' => $validated['target_audience'] ?? ['all'],
            'status' => $status,
        ]);

        return response()->json($announcement);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'priority'          => 'required|in:low,medium,high,urgent',
            'category'          => 'required|string|max:50',
            'content'           => 'required|string',
            'expires_at'        => 'nullable|date',
            'target_audience'   => 'nullable|array',
            'target_audience.*' => 'in:all,managers,department_heads',
            'department_id'     => 'nullable|exists:departments,department_id',
            'status'            => 'nullable|in:scheduled,published'
        ]);

        $announcement = Announcement::findOrFail($id);

        $status = $announcement->status; // keep old status by default
        if (!empty($validated['expires_at'])) {
            $status = now()->lt($validated['expires_at']) ? 'scheduled' : 'published';
        }

        $announcement->update($validated + [
            'target_audience' => $validated['target_audience'] ?? ['all'],
            'status' => $status,
        ]);

        return response()->json([
            'message' => 'Announcement updated successfully.',
            'announcement' => $announcement,
        ]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $announcement = Announcement::findOrFail($id);

        $announcement->delete();

        return redirect()->route('admin.announcements');
    }
}
