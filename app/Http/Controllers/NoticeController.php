<?php
// app/Http/Controllers/NoticeController.php
namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    // Remove constructor - middleware is handled in routes

    public function index()
    {
        $notices = Notice::latest()->get();
        return view('notices.index', compact('notices'));
    }

    public function create()
    {
        $types = [
            'general' => 'General Notice',
            'maintenance' => 'Maintenance Notice',
            'payment' => 'Payment Reminder',
            'urgent' => 'Urgent Notice'
        ];

        return view('notices.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,maintenance,payment,urgent',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Notice::create($validated);

        return redirect()->route('notices.index')
            ->with('success', 'Notice created successfully.');
    }

    public function show(Notice $notice)
    {
        return view('notices.show', compact('notice'));
    }

    public function edit(Notice $notice)
    {
        $types = [
            'general' => 'General Notice',
            'maintenance' => 'Maintenance Notice',
            'payment' => 'Payment Reminder',
            'urgent' => 'Urgent Notice'
        ];

        return view('notices.edit', compact('notice', 'types'));
    }

    public function update(Request $request, Notice $notice)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,maintenance,payment,urgent',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $notice->update($validated);

        return redirect()->route('notices.index')
            ->with('success', 'Notice updated successfully.');
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();

        return redirect()->route('notices.index')
            ->with('success', 'Notice deleted successfully.');
    }

    public function toggleStatus(Notice $notice)
    {
        $notice->update(['is_active' => !$notice->is_active]);

        $status = $notice->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('notices.index')
            ->with('success', "Notice {$status} successfully.");
    }

    public function active()
    {
        $notices = Notice::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            })
            ->latest()
            ->get();
            
        return view('notices.active', compact('notices'));
    }
}