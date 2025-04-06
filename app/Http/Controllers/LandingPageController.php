<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\LandingPage;
use App\Models\Advertisement;

class LandingPageController extends Controller
{
    public function myLandingpage()
    {
        $user = auth()->user();

        $landingPage = \App\Models\LandingPage::where('user_id', $user->id)->first();

        $order = $landingPage ? (is_array($landingPage->component_order) ? $landingPage->component_order : explode(',', $landingPage->component_order)) : [];

        return view('landingpage.my-landingpage', compact('landingPage', 'order'));
    }

    public function show($slug)
    {
        $landingPage = LandingPage::where('slug', $slug)->firstOrFail();
        $ads = Advertisement::where('advertiser_id', $landingPage->user_id)
            ->where(function($query) {
                $query->whereNull('inactive_at')
                    ->orWhere('inactive_at', '>', now());
            })->get();

        return view('landingpage.show', [
            'landingPage' => $landingPage,
            'ads' => $ads,
        ]);
    }

    public function save(Request $request)
    {
        $user = Auth::user();

        if ($user->user_type !== 2) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'information_text' => 'nullable|string',
            'custom_url' => 'required|string|alpha_dash|unique:landing_pages,slug,' . $user->id . ',user_id',
            'image' => 'nullable|image|max:2048',
            'component_order' => 'required|array',
            'component_order.*' => 'in:information,image,advertisements',
            'color' => 'nullable|string|size:7',
        ]);

        if (count(array_unique($validated['component_order'])) !== count($validated['component_order'])) {
            return back()->withErrors(['component_order' => 'Each component must be selected only once.']);
        }
        
        if (count($validated['component_order']) < 1 || count($validated['component_order']) > 3) {
            return back()->withErrors(['component_order' => 'You must select between 1 and 3 components.']);
        }

        $landingPage = LandingPage::where('user_id', $user->id)->first();
        
        if (!$landingPage) {
            $landingPage = new LandingPage();
            $landingPage->user_id = $user->id;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            if ($landingPage && $landingPage->image_path) {
                $oldImagePath = public_path('storage/' . $landingPage->image_path);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $imagePath = $request->file('image')->store('landingpage-images', 'public');
        }
        
        $landingPage->slug = $validated['custom_url'];
        $landingPage->info_content = $validated['information_text'];
        $landingPage->color = $validated['color'] ?? '#ffffff';
        if ($imagePath) {
            $landingPage->image_path = $imagePath;
        }
        $landingPage->component_order = $validated['component_order'];

        $landingPage->save();

        return redirect()->route('landingpage.show', ['slug' => $landingPage->slug]);
    }
}