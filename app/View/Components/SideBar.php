<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SideBar extends Component
{
    public $type;
    public $user;
    public $notification;

    public function __construct(Request $request)
    {
        $this->type = $request->routeIs('dashboard.*') ? 'dashboard' : '';

        switch ($this->type)
        {
            case 'dashboard':
                $this->user = Auth::user();
                $this->notification = User::withTrashed()->with(['notifications'])->where('email', env('MAIL_ADMINISTRATOR_EMAIL'))->first();
            break;
            default:
                $this->user = Auth::user();
                $this->notification = !empty($this->user) ? $this->user : null;
            break;
        }
    }

    public function render()
    {
        return view($this->type . '.' . 'components.side-bar', [
            'user' => $this->user,
            'notification' => $this->notification
        ]);
    }
}
