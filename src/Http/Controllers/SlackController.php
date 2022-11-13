<?php

namespace BrandonJBegle\SlackChannel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use JoliCode\Slack\ClientFactory;

class SlackController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = ClientFactory::create(config('slack-channel-nova-field.token'));
    }

    public function searchChannels(Request $request)
    {
        $term = $request->get('term');
        $types = $request->get('types');
//        ,private_channel Readme needs info aobut thsi

        $channels = Cache::remember('slackChannels-' . $types, 120, function () use ($types) {
            return collect($this->client->conversationsList(['types' => $types ?: 'public_channel'])->getChannels());
        });

        if ($term)
            $channels = $channels->filter(function ($channel) use ($term) {
                return strpos($channel->getName(), $term) !== false;
            });

        $channels = $channels->map(function ($channel) {
            return [
                'display' => $channel->getName(),
                'value'   => $channel->getId(),
                'type'    => $channel->getIsPrivate() ? 'private' : 'public'
            ];
        });

        return response()->json($channels);
    }

    public function refreshList(Request $request)
    {
        Cache::forget('slackChannels-' . $request->types);

        return response()->json();
    }
}