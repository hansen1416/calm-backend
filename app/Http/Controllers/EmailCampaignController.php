<?php

namespace App\Http\Controllers;

use App\Models\EmailCampaign;
use Illuminate\Http\Request;

class EmailCampaignController extends Controller
{

    public function store(Request $request)
    {
        // TODO auth: resolve current user_id
        // $userId = $request->user()->id;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'graph_json' => ['required', 'array'],
        ]);

        $campaign = new \App\Models\EmailCampaign();
        $campaign->user_id = 1; // TODO replace with $userId
        $campaign->name = $data['name'];
        $campaign->status = 'draft';
        $campaign->graph_json = $data['graph_json'];
        $campaign->save();

        return response()->json(['id' => $campaign->id], 201);
    }

    public function getGraph(Request $request, EmailCampaign $email_campaign)
    {
        // TODO: enforce auth/ownership (e.g., map Cognito sub -> users row)
        // Example if you have $request->user():
        // abort_unless($request->user()->id === $email_campaign->user_id, 403);

        return response()->json($email_campaign->graph_json);
    }

    public function updateGraph(Request $request, int $email_campaign_id)
    {
        // TODO: enforce auth/ownership
        // abort_unless($request->user()->id === $email_campaign->user_id, 403);
        $email_campaign = EmailCampaign::findOrFail($email_campaign_id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'graph_json' => ['required', 'array'],
        ]);

        $email_campaign->name = $data['name'];
        $email_campaign->graph_json = $data['graph_json'];
        $email_campaign->save();

        return response()->json([
            'ok' => true,
            'updated_at' => $email_campaign->updated_at,
        ]);
    }
}
