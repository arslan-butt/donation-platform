<?php

namespace App\Http\Controllers;

use App\Actions\Campaign\CreateCampaign;
use App\Actions\Campaign\UpdateCampaign;
use App\Enums\CampaignStatus;
use App\Http\Requests\Campaign\StoreCampaignRequest;
use App\Http\Requests\Campaign\UpdateCampaignRequest;
use App\Models\Campaign;
use App\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class CampaignController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Campaign::class, 'campaign');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $campaigns = Campaign::query()
            ->with(['author:id,name', 'category:id,name']) // Only select needed columns
            ->withDonationStats()
            ->forUser(auth()->user())
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->search($request->search);
            })
            ->filter([
                'category_id' => $request->category,
                'status' => $request->status,
                'featured' => $request->boolean('featured'),
                'date_range' => $request->date_range,
            ])
            ->latest()
            ->paginate($request->per_page ?? 10)
            ->through(fn ($campaign) => $campaign->formatForResponse());

        return Inertia::render('admin/campaigns/Index', [
            'campaigns' => $campaigns,
            'filters' => $request->only(['search', 'status', 'per_page']),
        ]);
    }

    public function all(Request $request)
    {
        $campaigns = Campaign::query()
            ->with(['author', 'category'])
            ->withDonationStats()
            ->whereStatus(CampaignStatus::Active)
            ->search($request->search)
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->filter([
                'category_id' => $request->category,
                'status' => $request->status,
                'featured' => $request->boolean('featured'),
                'date_range' => $request->date_range,
            ])
            ->latest()
            ->paginate($request->input('per_page', 10))
            ->through(fn ($campaign) => $campaign->formatForResponse());

        return Inertia::render('admin/campaigns/All', [
            'campaigns' => Inertia::merge($campaigns->items()),
            'pagination' => Arr::except($campaigns->toArray(), 'data'),
            'filters' => $request->only(['search', 'status', 'category', 'featured', 'date_range', 'per_page']),
            'categories' => Category::getForDropdown(),
            'statuses' => CampaignStatus::labels(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();

        if (! $user->hasRole('admin') && ! Gate::allows('create campaigns')) {
            abort(403);
        }

        return Inertia::render('admin/campaigns/Create', [
            'categories' => Category::getForDropdown(),
            'statuses' => CampaignStatus::labels(),

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCampaignRequest $request, CreateCampaign $action): RedirectResponse
    {

        $user = $request->user();

        if (! $user->hasRole('admin') && ! $user->can('create campaigns')) {
            abort(403);
        }

        try {
            $category = $action->execute(
                $request->validated(),
                $request->user()
            );

            return $this->redirectToCampaigns();

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign)
    {
        return Inertia::render('admin/campaigns/Edit', [
            'categories' => Category::getForDropdown(),
            'statuses' => CampaignStatus::labels(),
            'campaign' => $campaign,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCampaignRequest $request, Campaign $campaign, UpdateCampaign $action): RedirectResponse
    {
        try {
            $action->execute(
                $campaign,
                $request->validated(),
                $request->user()
            );

            return $this->redirectToCampaigns();

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong: '.$e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        $user = auth()->user();

        if (! Gate::authorize('delete own campaigns', $campaign)) {
            abort(403);
        }
        $campaign->delete();

        return $this->redirectToCampaigns();

    }

    protected function redirectToCampaigns(): RedirectResponse
    {
        return auth()->user()->hasRole('admin')
            ? to_route('admin.campaigns.index')
            : to_route('campaigns.index');
    }
}
