<?php
namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Mail\ElectionReportMail;
use App\Models\Election;
use Illuminate\Support\Facades\Mail;

class ReportingService
{
    public function generateElectionReport(Election $election)
    {
        $electionService = new ElectionService();
        $results = $electionService->calculateElectionResults($election);

        $pdf = PDF::loadView('reports.election_results', [
            'election' => $election,
            'results' => $results
        ]);

        $filename = "election_results_{$election->id}_" . now()->format('YmdHis') . '.pdf';
        
        // Save to storage
        Storage::put("reports/{$filename}", $pdf->output());

        // Log report generation
        app(AuditLogService::class)->log(
            'generate_report', 
            "Election results report generated for {$election->name}",
            $election
        );

        return $filename;
    }

    public function emailReport(Election $election, $recipients)
    {
        $filename = $this->generateElectionReport($election);
        
        foreach ($recipients as $recipient) {
            Mail::to($recipient)->send(
                new ElectionReportMail($election, $filename)
            );
        }
    }
}