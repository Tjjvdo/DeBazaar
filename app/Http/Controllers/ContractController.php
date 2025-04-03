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

    private function generateSampleContract($user_id)
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

    public function upload(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'contract_pdf' => 'required|file|mimes:pdf|max:2048',
        ]);

        $path = $request->file('contract_pdf')->store('contracts', 'public');

        $contract = Contract::where('user_id', $request->user_id)->first();
        if ($contract) {
            $contract->update([
                'pdf_path' => $path,
                'status' => 'pending',
            ]);
        }

        return redirect()->back()->with('success', 'Contract uploaded successfully.');
    }

    public function showAdvertiserContract()
    {
        $contract = Contract::where('user_id', auth()->id())->first();

        return view('contracts.advertiser-contract', compact('contract'));
    }

    public function respondToContract(Request $request)
    {
        $request->validate([
            'response' => 'required|in:accept,decline',
        ]);

        $contract = Contract::where('user_id', auth()->id())->first();
        if (!$contract) {
            return redirect()->back()->with('error', 'No contract found.');
        }

        $status = "";
        if($request == "accept"){
            $status = "accepted";
        } elseif ($request == "decline") {
            $status = "declined";
        }

        $contract->status = $status;
        $contract->save();

        return redirect()->route('contracts.advertiser')->with('success', 'Your response has been recorded.');
    }
}
