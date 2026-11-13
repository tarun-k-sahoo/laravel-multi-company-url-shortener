<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role->slug;

        $query = ShortUrl::with(['company', 'user'])->latest();

        if ($role === 'super-admin') {
            // SuperAdmin can see all companies' URLs.
        } elseif ($role === 'admin') {
            $query->where('company_id', $user->company_id);
        } elseif ($role === 'member') {
            $query->where('user_id', $user->id);
        } else {
            abort(403, 'You are not allowed to view short URLs.');
        }

        $shortUrls = $query->paginate(10);

        return view('short_urls.index', compact('shortUrls'));
    }

    public function create()
    {
        $role = auth()->user()->role->slug;

        if (!in_array($role, ['admin', 'member'])) {
            abort(403, 'You are not allowed to create short URLs.');
        }

        return view('short_urls.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $role = $user->role->slug;

        if (!in_array($role, ['admin', 'member'])) {
            abort(403, 'You are not allowed to create short URLs.');
        }

        if (!$user->company_id) {
            abort(403, 'Your account is not assigned to a company.');
        }

        $validated = $request->validate([
            'original_url' => 'required|url|max:2048',
        ]);

        do {
            $shortCode = Str::random(6);
        } while (ShortUrl::where('short_code', $shortCode)->exists());

        ShortUrl::create([
            'company_id' => $user->company_id,
            'user_id' => $user->id,
            'original_url' => $validated['original_url'],
            'short_code' => $shortCode,
        ]);

        return redirect()
            ->route('short-urls.index')
            ->with('success', 'Short URL created successfully.');
    }

    public function redirectToOriginal($shortCode)
    {
        $shortUrl = ShortUrl::where('short_code', $shortCode)->firstOrFail();

        return redirect()->away($shortUrl->original_url);
    }
}
