<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuditRequest;
use App\Http\Resources\AuditReportResource;
use App\Mail\AuditReportMail;
use App\Models\Appliance;
use App\Models\AuditReport;
use App\Models\Building;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AuditController extends Controller
{
    /**
     * POST /api/audits
     *
     * Creates (or updates) a building, replaces its appliance checklist,
     * generates a fresh AuditReport, and — if an email was supplied —
     * queues the audit report email.
     */
    public function store(StoreAuditRequest $request): JsonResponse
    {
        $data = $request->validated();
        $ratePerKwh = $data['rate_per_kwh'] ?? 0.14;

        $report = DB::transaction(function () use ($data, $ratePerKwh) {
            $building = Building::create([
                'name' => $data['building']['name'],
                'address' => $data['building']['address'] ?? null,
                'building_type' => $data['building']['building_type'] ?? 'Office',
                'square_footage' => $data['building']['square_footage'],
                'floors' => $data['building']['floors'] ?? 1,
                'occupants' => $data['building']['occupants'] ?? 0,
            ]);

            foreach ($data['appliances'] as $row) {
                Appliance::create([
                    'building_id' => $building->id,
                    'name' => $row['name'],
                    'category' => $row['category'] ?? 'Other',
                    'wattage' => $row['wattage'],
                    'quantity' => $row['quantity'],
                    'hours_per_day' => $row['hours'],
                    'is_active' => $row['checked'] ?? true,
                ]);
            }

            return AuditReport::generateForBuilding($building->fresh('appliances'), $ratePerKwh);
        });

        if (! empty($data['email'])) {
            Mail::to($data['email'])->send(new AuditReportMail($report->load('building')));
        }

        return (new AuditReportResource($report->load('building')))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * GET /api/audits/{auditReport}
     */
    public function show(AuditReport $auditReport): JsonResponse
    {
        return (new AuditReportResource($auditReport->load('building')))
            ->response();
    }

    /**
     * POST /api/audits/{auditReport}/resend-email
     */
    public function resendEmail(AuditReport $auditReport): JsonResponse
    {
        $email = request()->validate(['email' => ['required', 'email']])['email'];

        Mail::to($email)->send(new AuditReportMail($auditReport->load('building')));

        return response()->json(['message' => "Report queued for delivery to {$email}."]);
    }
}
