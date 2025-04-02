<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Contract;
use Barryvdh\DomPDF\Facade\Pdf;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::all(); 
        return view('business-contracts', compact('contracts'));
    }

    public function download($user_id)
    {
        if (auth()->user()->user_type !== 3) {
            abort(403, 'Unauthorized access');
        }

        $contract = Contract::where('user_id', $user_id)->first();

        if (!$contract) {
            return back()->with('error', 'No contract found for this user.');
        }

        return $this->generateSampleContract($user_id);
    }

    public function generateSampleContract($user_id)
    {
        $contract = Contract::where('user_id', $user_id)->first();
        if (!$contract) {
            return back()->with('error', 'No contract found for this user.');
        }

        $businessName = $contract->user->name ?? 'Unknown Business';

        $data = [
            'user_id' => $user_id,
            'date' => now()->format('Y-m-d'),
            'company' => $businessName,
        ];

        $pdf = Pdf::loadView('contracts.template', $data);

        return $pdf->download("contract_user_{$businessName}.pdf");
    }
}
