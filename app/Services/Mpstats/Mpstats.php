<?php

namespace App\Services\Mpstats;

use App\Models\Mpstat;
use App\Models\Sell;
use App\Services\Telegram\Telegram;

class Mpstats
{
        public function check($telegram_id, $chat_id){
            $sell = Sell::where('telegram_id', $telegram_id)->first();
            if ($sell && $sell->status == 'active'|| $chat_id == '-1002119843721'){
                $mpstats = Mpstat::where('telegram_id', $telegram_id)->first();
                if ($mpstats){
                    return $mpstats;
                }
                $mp = Mpstat::where('telegram_id', null)->first();
                $mp->telegram_id = $telegram_id;
                $mp->save();
                return $mp ?? ['error' => 'Хатоги барои хали масала ба @dumpanddie мурочиат кунед!'];
            }
            return ['error' => 'Хатоги шуморо мо дар гуруххоамон наефтем барои хали масала ба @dumpanddie мурочиат кунед!'];
        }

        public function access(){
            $telegram_id = request()->post('user')['id'];
            $chat_id = request()->post('chat_id');
            return $this->check($telegram_id, $chat_id);
        }

        public function send(){
            $sells = Sell::where('status', 'active')->where('chat_id', '!=', null)->get();
            foreach ($sells as $sell) {
               (new Telegram())->mpstats($this->check($sell->telegram_id, $sell->chat_id));
            }
        }


}
