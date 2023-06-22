<?php

namespace App\Http\Controllers;

use App\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function SendMessage(Request $request)
    {
        $text = 'Пользователь: ' . auth()->user()->name_ru . '
Hashtag: #user' . auth()->user()->id . '
IP: "' . $request->ip() . '"
Page: "' . $request->get('url') . '"
Message:

' . $request->get('message');

        $message = Telegram::sendMessage([
            'chat_id' => env('SUPPORT_CHAT_ID'),
            'disable_web_page_preview' => true,
            'text' => $text
        ]);

        $startPos = strpos($message['text'], 'Message:');
        $msg = substr($message['text'], $startPos + 9);

        $data = [
            'chat_id' => $message['chat']['id'],
            'message_id' => $message['message_id'],
            'author' => auth()->user()->id,
            'user' => auth()->user()->id,
            'text' => $msg,
        ];

        SupportMessage::create($data);

        return '<div class="row msg_container base_receive">
                                    <div class="col-md-10 col-xs-10">
                                        <div class="messages msg_receive">
                                            <p>' . $msg . '</p>
                                            <time datetime="2009-11-13T20:00">' . auth()->user()->name_ru . '</time>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-2 avatar pd-0-force">
                                        <img src="/img/avatar/no.png"
                                             class="img-responsive">
                                    </div>
                                </div>';
    }

}
