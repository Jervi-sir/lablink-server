<?php

namespace App\Http\Controllers;

class LabContractController extends Controller
{
    public function show()
    {
        return view('pdf/lab-student-contract-template', [
            'labName' => 'مخبر السلام للتحليلات',
            'labCommercialRegister' => '16/00-1234567B22',
            'consumerName' => 'أحمد بن علي',
            'consumerPhone' => '0555 12 34 56',
            'serviceName' => 'استعمال جهاز PCR',
            'bookingDate' => now()->format('Y-m-d'),
            'totalPrice' => '12,000 دج',
            'appName' => 'LabLink',
        ]);
    }
}
