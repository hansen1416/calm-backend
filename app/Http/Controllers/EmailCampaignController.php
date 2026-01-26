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
            'mode' => ['required', 'in:include,exclude'],
            'kind' => ['required', 'in:contact,group'],
            'entity_id' => ['required', 'integer'],
            'graph_json' => ['required', 'array'],
        ]);

        $campaign = new \App\Models\EmailCampaign();
        $campaign->user_id = 1; // TODO replace with $userId
        $campaign->name = $data['name'];
        $campaign->status = 'draft';
        $campaign->mode = $data['mode'];
        $campaign->kind = $data['kind'];
        $campaign->entity_id = $data['entity_id'];
        $campaign->graph_json = $data['graph_json'];
        $campaign->save();

        return response()->json(['id' => $campaign->id], 201);
    }

    public function getGraph(Request $request, EmailCampaign $email_campaign)
    {
        // TODO: enforce auth/ownership (e.g., map Cognito sub -> users row)
        // Example if you have $request->user():
        // abort_unless($request->user()->id === $email_campaign->user_id, 403);

        return response()->json([
            'name' => $email_campaign->name,
            'status' => $email_campaign->status,
            'mode' => $email_campaign->mode,
            'kind' => $email_campaign->kind,
            'entity_id' => $email_campaign->entity_id,
            'graph_json' => $email_campaign->graph_json,
        ]);
    }

    public function updateGraph(Request $request, int $email_campaign_id)
    {
        // TODO: enforce auth/ownership
        // abort_unless($request->user()->id === $email_campaign->user_id, 403);
        $email_campaign = EmailCampaign::findOrFail($email_campaign_id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mode' => ['required', 'in:include,exclude'],
            'kind' => ['required', 'in:contact,group'],
            'entity_id' => ['required', 'integer'],
            'graph_json' => ['required', 'array'],
        ]);

        $email_campaign->name = $data['name'];
        $email_campaign->graph_json = $data['graph_json'];
        $email_campaign->mode = $data['mode'];
        $email_campaign->kind = $data['kind'];
        $email_campaign->entity_id = $data['entity_id'];
        $email_campaign->save();

        return response()->json([
            'ok' => true,
            'updated_at' => $email_campaign->updated_at,
        ]);
    }
}
