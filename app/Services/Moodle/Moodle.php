<?php

namespace App\Services\Moodle;

use App\Models\MoodleClient;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use \GuzzleHttp\Psr7\Request as Req;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mockery\Exception;

class Moodle
{
    public function create_user(Request $request){
        try {
            $moodleUser = MoodleClient::where('telegram_id', $request['user']['id'])->first();
            if(empty($moodleUser)){
                $client = new Client();
                $headers = [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ];
                $password = Str::password(8, true, true, false, ) . "Al@";
                $options = [
                    'form_params' => [
                        'wstoken' => config('services.moodle.token'),
                        'moodlewsrestformat' => 'json',
                        'wsfunction' => 'core_user_create_users',
                        'users[0][username]' =>  $request['user']['id'],
                        'users[0][password]' => $password,
                        'users[0][firstname]' => $request['user']['first_name'] == NULL ? '/' : $request['user']['first_name'],
                        'users[0][lastname]' => $request['user']['last_name'] == NULL ? '/' : $request['user']['last_name'],
                        'users[0][email]' => $request['user']['id'] . '@students.alaboom.org',
                        'users[0][auth]' => 'manual'
                    ]
                ];
                $req = new Req('POST', 'https://kurs.alaboom.org/webservice/rest/server.php', $headers);
                $res = $client->sendAsync($req, $options)->wait();
                $resData = json_decode($res->getBody(), true);
                $this->add_client($request, $resData, $password);
                $course_id = $this->getCourseId($request['chat_id']);
                $this->add_to_course($resData[0]['id'], $course_id);
                $moodleUser = MoodleClient::where('telegram_id', $request['user']['id'])->first();
                return [
                    [
                        'login' => $moodleUser['telegram_id'],
                        'password' => $moodleUser['password']
                    ]
                ];
            }
            return [
                [
                    'login' => $moodleUser['telegram_id'],
                    'password' => $moodleUser['password']
                ]
            ];

        } catch (Exception $e){
            Log::info($e->getMessage());
        }

    }
    public function add_to_course($moodle_id, $course_id){

        try {
            $client = new Client();
            $headers = [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ];
            $options = [
                'form_params' => [
                    'wstoken' => config('services.moodle.token'),
                    'moodlewsrestformat' => 'json',
                    'wsfunction' => 'enrol_manual_enrol_users',
                    'enrolments[0][roleid]' => '5',
                    'enrolments[0][userid]' => $moodle_id,
                    'enrolments[0][courseid]' => $course_id
                ]];
            $request = new Req('POST', 'https://kurs.alaboom.org/webservice/rest/server.php', $headers);
            $client->sendAsync($request, $options)->wait();
        }catch (\Exception $exception){
            Log::info($exception->getMessage());
        }

    }
    public function getCourseId($group_id){
        switch ($group_id){
            case "-1001925452270":
                return 9;
            case "-1002144677415":
                return 8;
            case "-1002056611872":
                return 10;
            case "-1002131730381":
            case "-1002134641485":
            case "-1002070555735":
                return 11;
        }

    }
    public function add_client($user, $moodle, $password){
      return   MoodleClient::updateOrCreate(
            [
                'telegram_id' => $user['user']['id']
            ],
            [
                'telegram_id' => $user['user']['id'],
                'moodle_id' => $moodle[0]['id'],
                'first_name' => $user['user']['first_name'] == NULL ? '/' : $user['user']['first_name'],
                'last_name' => $user['user']['last_name'] == NULL ? '/' : $user['user']['last_name'],
                'user_name' => $user['user']['username'] == NULL ? '/' : $user['user']['username'],
                'password' => $password,
                'tariff' => $user['tariff']
            ]
        );
    }
}
