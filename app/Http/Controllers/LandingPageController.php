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

    public function show($slug, Request $request)
    {
        $landingPage = LandingPage::where('slug', $slug)->firstOrFail();

        $query = Advertisement::where('inactive_at', '>', now());

        if ($request->has('title') && !empty($request->title)) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->has('price_min') && is_numeric($request->price_min)) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->has('price_max') && is_numeric($request->price_max)) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->has('is_rentable') && in_array($request->is_rentable, ['0', '1'])) {
            $query->where('is_rentable', $request->is_rentable);
        }

        $ads = $query->paginate(3)->withQueryString();

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

        $requiresImage = $request->has('component_order') && in_array('image', (array) $request->component_order);

        $validated = $request->validate([
            'information_text' => 'nullable|string',
            'custom_url' => 'required|string|alpha_dash|unique:landing_pages,slug,' . $user->id . ',user_id',
            'image' => ($requiresImage ? 'required|' : 'nullable|') . '|image|max:2048',
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
