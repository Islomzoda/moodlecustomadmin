<?php

namespace App\Services\Telegram;

use App\Models\Sell;
use App\Services\Moodle\Moodle;
use GuzzleHttp\Client;

class Telegram
{
    protected $telegramApiUrl = 'https://api.telegram.org/bot6406496260:AAGeZS22r6fcfI2hQoNJ6_0fzLKCg5ARXd4/';

    public function removeUser($telegram_id = "")
    {
        $telegramId = $telegram_id !== "" ? $telegram_id : request()->post('telegram_id');
        $removedChats = "";
        $client = new Client();

        foreach (config('services.telegram.CHAT_IDS') as $chatId) {
            try {
                $response = $client->request('GET', $this->telegramApiUrl . 'getChatMember', [
                    'query' => [
                        'chat_id' => $chatId,
                        'user_id' => $telegramId,
                    ],
                ]);

                $member = json_decode($response->getBody()->getContents(), true);

                if ($member['result']['status'] !== 'left' && $member['result']['status'] !== 'kicked') {
                    $client->request('POST', $this->telegramApiUrl . 'kickChatMember', [
                        'form_params' => [
                            'chat_id' => $chatId,
                            'user_id' => $telegramId,
                        ],
                    ]);
                    if ($chatId != '-1002112634327' && $chatId != '-1002070555735'){
                        $client->request('POST', $this->telegramApiUrl . 'sendMessage', [
                            'form_params' => [
                                'chat_id' => $telegramId, // ID пользователя для отправки сообщения
                                'text' => "Ассалому алейкум шумо мувафакатан аз курс хорич карда шудед барои востановит кардан ба менеджер мурочиат кунед! @menej_tj",
                            ],
                        ]);
                        $removedChats = $chatId;
                    }

                }
            } catch (\Exception $e) {
                return ['error' => $e->getMessage()];
            }
        }

        return ['removed_from_chat_id' => $removedChats];
    }

    public function addUser($telegram_id = "", $chat_id = "")
    {
        $telegramId = $telegram_id !== "" ? $telegram_id : request()->post('telegram_id');
        $chatId = $chat_id !== "" ? $chat_id : request()->post('chat_id');
        $this->get_groups($telegramId, $chatId);
    }
    public function addUserToGroup($telegramId, $chatId, $message)
    {
        $client = new Client();

        try {

            // Создаем одноразовую ссылку для приглашения в группу
            $client->request('POST', $this->telegramApiUrl . 'unbanChatMember', [
                'form_params' => [
                    'chat_id' => $chatId,
                    'user_id' => $telegramId, // Сделаем ссылку одноразовой
                ],
            ]);
              // Создаем одноразовую ссылку для приглашения в группу
            $response = $client->request('POST', $this->telegramApiUrl . 'createChatInviteLink', [
                'form_params' => [
                    'chat_id' => $chatId,
                    'member_limit' => 1, // Сделаем ссылку одноразовой
                ],
            ]);

            $linkData = json_decode($response->getBody()->getContents(), true);
            $inviteLink = $linkData['result']['invite_link'];
            // Отправляем созданную ссылку пользователю через личное сообщение
            $client->request('POST', $this->telegramApiUrl . 'sendMessage', [
                'form_params' => [
                    'chat_id' => $telegramId, // ID пользователя для отправки сообщения
                    'text' => "$message: $inviteLink",
                ],
            ]);

            return ["message" => "Пользователь {$telegramId} добавлен в группу {$chatId}. Ссылка для входа отправлена."];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }


    public function get_groups($telegram_id, $chatId){
            switch ($chatId){
                case "-1001925452270":
                    $this->addUserToGroup($telegram_id, $chatId, 'Шумо доступ ба курсро востановит кардед ин ссылкаи гурухи шумо');
                    $this->addUserToGroup($telegram_id, '-1002112634327', 'ин гурухи общий поток');
                case "-1002144677415":
                    $this->addUserToGroup($telegram_id, $chatId, 'Шумо доступ ба курсро востановит кардед ин ссылкаи гурухи шумо');
                    $this->addUserToGroup($telegram_id, '-1002112634327', 'ин гурухи общий поток');
                case "-1002056611872":
                    $this->addUserToGroup($telegram_id, $chatId, 'Шумо доступ ба курсро востановит кардед ин ссылкаи гурухи шумо');
                    $this->addUserToGroup($telegram_id, '-1002112634327', 'ин гурухи общий поток');
                case "-1002131730381":
                case "-1002134641485":
                case "-1001995332874":
                case "-1002072859818":
                    $this->addUserToGroup($telegram_id, $chatId, 'Шумо доступ ба курсро востановит кардед ин ссылкаи гурухи шумо');
                    $this->addUserToGroup($telegram_id, '-1002112634327', 'ин гурухи общий поток');
                    $this->addUserToGroup($telegram_id, '-1002070555735', 'ин гурухи общий наставничество');
            }

    }
    public function check(){
        $sell_id = request()->id;
        $sell = Sell::where('id', $sell_id)->first();
        if ($sell->status == 'active' && $sell->chat_id != null){
            $this->addUser($sell->telegram_id, $sell->chat_id);
            (new Moodle())->reactivate_user($sell->telegram_id);
        }else{
            $res = $this->removeUser($sell->telegram_id);
            Sell::where('id', $sell_id)->update(['chat_id' => $res['removed_from_chat_id']]);
            (new Moodle())->deactivate_user($sell->telegram_id);
        }
     return back();
    }
}
