<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Conversation;
use App\Models\Message;

class MongoChatMigrationSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Migrating chats from MySQL to MongoDB...');

        // 1. Get old conversations from MySQL
        $oldConversations = DB::connection('mysql')->table('conversations')->get();
        
        foreach ($oldConversations as $oldConv) {
            // Create in Mongo
            $newConv = Conversation::create([
                'learner_id' => $oldConv->learner_id,
                'tutor_id'   => $oldConv->tutor_id,
                'created_at' => $oldConv->created_at,
                'updated_at' => $oldConv->updated_at,
            ]);

            // 2. Get messages for this conversation from MySQL
            $oldMessages = DB::connection('mysql')->table('messages')
                ->where('conversation_id', $oldConv->id)
                ->get();

            foreach ($oldMessages as $oldMsg) {
                Message::create([
                    'conversation_id' => $newConv->id, // New Mongo string ID
                    'sender_id'       => $oldMsg->sender_id,
                    'body'            => $oldMsg->body,
                    'is_read'         => $oldMsg->is_read,
                    'created_at'      => $oldMsg->created_at,
                    'updated_at'      => $oldMsg->updated_at,
                ]);
            }
        }

        $this->command->info('Chat migration to MongoDB completed successfully!');
    }
}
