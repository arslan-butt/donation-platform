<?php

namespace App\Http\Controllers;

use App\Actions\Donation\CreateDonation;
use App\Http\Requests\Donation\StoreDonationRequest;

class DonationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDonationRequest $request, CreateDonation $action)
    {

        try {
            $validated = $request->validated();

            $donation = $action->execute($validated, auth()->user());

            return redirect()->back()->with('success', 'Donation recorded successfully!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong: '.$e->getMessage()])
                ->withInput();
        }

    }
}
