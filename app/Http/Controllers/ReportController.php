<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function show(Trip $trip)
    {
        if (!$trip->canBeViewedBy(Auth::user())) abort(403);

        $destinations = $trip->destinations;
        $budgets = $trip->budgets;
        $totalSpent = $budgets->sum('amount');
        $remainingBudget = $trip->total_budget - $totalSpent;

        $expensesByCategory = $budgets->groupBy('category')->map(function ($items) {
            return $items->sum('amount');
        });

        $categories = $expensesByCategory->keys();
        $amounts = $expensesByCategory->values();

        return view('reports.show', compact('trip', 'destinations', 'budgets', 'totalSpent', 'remainingBudget', 'categories', 'amounts'));
    }

    public function downloadPdf(Trip $trip)
    {
        if (!$trip->canBeViewedBy(Auth::user())) abort(403);

        $destinations = $trip->destinations;
        $budgets = $trip->budgets;
        $totalSpent = $budgets->sum('amount');
        $remainingBudget = $trip->total_budget - $totalSpent;

        $pdf = Pdf::loadView('reports.pdf', compact('trip', 'destinations', 'budgets', 'totalSpent', 'remainingBudget'));
        return $pdf->download('trip_report_' . $trip->id . '.pdf');
    }
}