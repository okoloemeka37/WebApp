<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppGroupService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $instanceName;

    public function __construct()
    {
        $this->baseUrl = config('services.whatsapp.url');
        $this->apiKey = config('services.whatsapp.key');
        $this->instanceName = config('services.whatsapp.instance');
    }

    public function fetchAllGroups(): array
    {
        try {
            $response = Http::timeout(80)->withHeaders([
                'apikey' => $this->apiKey,
            ])->get("{$this->baseUrl}/group/fetchAllGroups/{$this->instanceName}?getParticipants=false");
            Log::info($response);
            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch groups: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Fetch message history for a specific group JID on demand.
     * 
     * @param string $remoteJid
     * @return array
     */
    public function getGroupMessages(string $remoteJid): array
    {
        try {
            $response = Http::timeout(80)->withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/chat/findMessages/{$this->instanceName}", [
                'where' => [
                    'key' => [
                        'remoteJid' => $remoteJid,
                    ]
                ]
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Failed to fetch group messages: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Group Messages Exception: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Fetch all synchronized contacts from the gateway session.
     */
    public function fetchAllContacts(): array
    {
        try {
            // Some versions use GET, others use POST with empty body. 
            // Using POST to /chat/findContacts/{instanceName} with empty parameters usually returns all records.
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/chat/findContacts/{$this->instanceName}", [
                'where' => (object) [] // Forces an empty JSON object {} instead of an array []
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Failed to fetch contacts: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Fetch Contacts Exception: ' . $e->getMessage());
        }

        return [];
    }


public function fetchGroupInfo(string $groupId): array
    {
        try {
            // This endpoint fetches full group details including participants
            $response = Http::timeout(30)->withHeaders([
                'apikey' => $this->apiKey,
            ])->get("{$this->baseUrl}/group/findGroupInfos/{$this->instanceName}", [
                'groupJid' => $groupId
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Failed to fetch group info: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Group info Exception: ' . $e->getMessage());
        }

        return [];
    }



    public function sendGroupMessage(string $remoteJid, string $messageText): ?array
{
    try {
        $response = Http::withHeaders([
            'apikey' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/message/sendText/{$this->instanceName}", [
            'number' => $remoteJid,
            'text' => $messageText,
            'delay' => 1200, 
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('WhatsApp Gateway Error: ' . $response->body());
        throw new \Exception($response->body());
    } catch (\Exception $e) {
        Log::error('WhatsApp Service Exception: ' . $e->getMessage());

       throw new \Exception($e->getMessage());
    }
}


/**
     * Reply to a specific message in a group by referencing its ID.
     */
    public function replyToGroupMessage(string $remoteJid, string $messageText, string $quotedMessageId, string $participantJid, bool $fromMe = false): ?array
    {
        try {
            $response = Http::timeout(30)->withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/message/sendText/{$this->instanceName}", [
                'number' => $remoteJid,
                'text' => $messageText,
                'quoted' => [
                    'key' => [
                        'id' => $quotedMessageId,       // The ID of the message being replied to
                        'remoteJid' => $remoteJid,       // The group JID
                        'fromMe' => $fromMe,             // Was the original message sent by you or them?
                        'participant' => $participantJid // Who sent the original message
                    ]
                ]
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('WhatsApp Reply Error: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('WhatsApp Reply Exception: ' . $e->getMessage());
        }

        return null;
    }
}
