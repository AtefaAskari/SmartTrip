<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function store(Request $request, Trip $trip)
{
    $this->authorizeEdit($trip);
    $validated = $request->validate([
        'category' => 'required|string',
        'amount' => 'required|numeric|min:0.01',
        'expense_date' => 'required|date',
        'description' => 'nullable|string',
    ]);
    $validated['amount'] = (float) $validated['amount'];
    $validated['trip_id'] = $trip->id;
    Budget::create($validated);
    return redirect()->route('trips.show', $trip)->with('success', 'Expense added.');
}

    public function edit(Trip $trip, Budget $budget)
    {
        if ($budget->trip_id !== $trip->id) abort(404);
        $this->authorizeEdit($trip);
        return view('budgets.edit', compact('trip', 'budget'));
    }

    public function update(Request $request, Trip $trip, Budget $budget)
    {
        if ($budget->trip_id !== $trip->id) abort(404);
        $this->authorizeEdit($trip);

        $validated = $request->validate([
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $validated['amount'] = (float) $validated['amount'];
        $budget->update($validated);

        return redirect()->route('trips.show', $trip)->with('success', 'Expense updated.');
    }

    public function destroy(Trip $trip, Budget $budget)
    {
        if ($budget->trip_id !== $trip->id) abort(404);
        $this->authorizeEdit($trip);
        $budget->delete();
        return redirect()->route('trips.show', $trip)->with('success', 'Expense deleted.');
    }

    private function authorizeEdit($trip)
    {
        $canEdit = ($trip->user_id === Auth::id() 
            || $trip->collaborators()->where('user_id', Auth::id())->whereIn('role', ['editor', 'admin'])->exists());

        if (!$canEdit) abort(403);
    }
}