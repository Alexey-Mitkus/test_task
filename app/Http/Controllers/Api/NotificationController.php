<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;

use \Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class NotificationController extends BaseController
{
    public function store(Request $request)
    {
        $this->validate($request, $rules = [
            'type' => 'required',
            'message' => 'required',
            'subject' => 'required',
            'users' => 'required|array',
        ], [
            'type.required' => 'Необходимо указать тип',
            'message.required' => 'Необходимо заполнить текст сообщения',
            'subject.required' => 'Необходимо заполнить тему сообщения',
            'users.required' => 'Необходимо заполнить список получаетелей',
            'users.array' => 'Список получателей должен быть массивом'
        ]);

        $user = $request->user('api');

        switch ($request->input('type'))
        {
            case 'dashboard-user-messanges':
                if( !empty($request->input('users')) )
                {
                    foreach($request->input('users') as $key => $u):
                        \App\Models\User::find($u)->notify(new \App\Notifications\UserMessageNotification(
                            $subject = $request->input('subject'),
                            $message = $request->input('message'),
                            $sender = \App\Models\User::withTrashed()->where('email', env('MAIL_ADMINISTRATOR_EMAIL'))->first()
                        ));
                    endforeach;
                }
                return $this->sendResponse([]);
            break;
            default:
                return $this->sendError('Данный тип уведомлений отсутствует', $errorMessages = [], $code = 200);
            break;
        }
        dd($user, $request->all());

    }

    public function readNotifications(DatabaseNotification $notification, Request $request)
    {
        $user = $request->user('api');

        if( $user->hasRole('admin') || ( ( $notification->notifiable_type == 'App\Models\User' || $notification->notifiable_type == 'App\\Models\\User' ) && $notification->notifiable_id == $user->id ) )
        {
            if( empty($notification->read_at) )
            {
                $notification->markAsRead();
            }
        }

        return $this->sendResponse([]);
    }

    public function unreadNotifications(DatabaseNotification $notification, Request $request)
    {
        $user = $request->user('api');

        if( $user->hasRole('admin') || ( ( $notification->notifiable_type == 'App\Models\User' || $notification->notifiable_type == 'App\\Models\\User' ) && $notification->notifiable_id == $user->id ) )
        {
            if( !empty($notification->read_at) )
            {
                $notification->update([
                    'read_at' => null
                ]);
            }
        }

        return $this->sendResponse([]);
    }

    public function destroy(DatabaseNotification $notification, Request $request)
    {
        $user = $request->user('api');

        if( $user->hasRole('admin') || ( ( $notification->notifiable_type == 'App\Models\User' || $notification->notifiable_type == 'App\\Models\\User' ) && $notification->notifiable_id == $user->id ) )
        {
            $notification->delete();
        }

        return $this->sendResponse([]);
    }
}
