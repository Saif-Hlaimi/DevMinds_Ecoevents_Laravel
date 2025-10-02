<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupMember;
use App\Services\InspirationService;
use App\Services\ModerationService;
use App\Services\TextToSpeechService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupToolsController extends Controller
{
    public function inspireGeneric(Request $request, InspirationService $ai)
    {
        $data = $request->validate(['prompt'=>['required','string','max:500']]);
        $suggestion = $ai->suggest($data['prompt'], 'General');
        return response()->json(['ok'=>true,'text'=>$suggestion]);
    }

    public function inspire(Request $request, string $slug, InspirationService $ai)
    {
        $group = Group::where('slug',$slug)->firstOrFail();
        $this->ensureMember($group);
        $data = $request->validate(['prompt'=>['required','string','max:500']]);
        $suggestion = $ai->suggest($data['prompt'], 'Group: '.$group->name);
        return response()->json(['ok'=>true,'text'=>$suggestion]);
    }

    public function moderate(Request $request, ModerationService $mod)
    {
        $data = $request->validate(['text'=>['required','string','max:5000']]);
        $bad = $mod->hasBadWords($data['text']);
        return response()->json(['ok'=>true,'bad'=>$bad]);
    }

    public function tts(Request $request, string $slug, TextToSpeechService $tts)
    {
        $group = Group::where('slug',$slug)->firstOrFail();
        $this->ensureMember($group);
        $data = $request->validate([
            'text'=>['required','string','max:5000'],
            'voice'=>['nullable','string','max:100']
        ]);
        $audioB64 = $tts->synthesize($data['text'], $data['voice'] ?? null);
        $debug = [];
        if (config('app.debug')) {
            $cfg = config('services.azure_speech');
            $debug = [
                'region' => $cfg['region'] ?? null,
                'endpoint_set' => !empty($cfg['endpoint']),
                'voice' => $cfg['voice'] ?? null,
                'has_audio' => !empty($audioB64),
            ];
        }
        return response()->json(['ok'=>true,'audio'=>$audioB64,'debug'=>$debug]);
    }

    private function ensureMember(Group $group)
    {
        $ok = GroupMember::where('group_id',$group->id)
            ->where('user_id',Auth::id())
            ->where('status','approved')->exists();
        abort_unless($ok, 403);
    }
}
