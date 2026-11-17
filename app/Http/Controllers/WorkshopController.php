<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkshopController extends Controller
{
    public function home(): View
    {
        $featuredWorkshops = Workshop::withCount('registrations')
            ->where('is_featured', true)
            ->latest('date')
            ->take(3)
            ->get();

        $stats = [
            'workshops_count' => Workshop::count(),
            'participants_count' => User::where('role', 'participant')->count(),
            'tickets_count' => Ticket::where('status', 'valid')->count(),
            'categories_count' => Workshop::distinct('category')->count('category'),
        ];

        $categories = Workshop::select('category')
            ->distinct()
            ->pluck('category');

        return view('home', compact('featuredWorkshops', 'stats', 'categories'));
    }

    public function index(Request $request): View
    {
        $query = Workshop::withCount(['registrations' => function ($q) {
            $q->where('status', '!=', 'cancelled');
        }]);

        // Search query
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('instructor_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Sorting
        $sort = $request->input('sort', 'date_asc');
        match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'newest' => $query->latest('id'),
            default => $query->orderBy('date', 'asc'),
        };

        $workshops = $query->paginate(9)->withQueryString();

        $categories = Workshop::select('category')
            ->distinct()
            ->pluck('category');

        return view('workshops.index', compact('workshops', 'categories'));
    }

    public function show(string $slug): View
    {
        $workshop = Workshop::withCount(['registrations' => function ($q) {
            $q->where('status', '!=', 'cancelled');
        }])->where('slug', $slug)->firstOrFail();

        $existingRegistration = null;
        if (auth()->check()) {
            $existingRegistration = Registration::with(['payment', 'ticket'])
                ->where('user_id', auth()->id())
                ->where('workshop_id', $workshop->id)
                ->first();
        }

        $relatedWorkshops = Workshop::withCount('registrations')
            ->where('category', $workshop->category)
            ->where('id', '!=', $workshop->id)
            ->take(3)
            ->get();

        return view('workshops.show', compact('workshop', 'existingRegistration', 'relatedWorkshops'));
    }
}
