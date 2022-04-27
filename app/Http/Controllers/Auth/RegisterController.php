<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserField;
use App\Models\Organization;
use App\Models\EventLog;
use App\Models\EventLogCategory;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    public function redirectTo()
    {
        return route('user.index');
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        SEOMeta::setTitle('Регистрация');
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        SEOMeta::setTitle('Главная');
        // SEOMeta::setDescription('');
        // SEOMeta::setKeywords('');

        return view('auth.register');
    }

    public function checkIsUnique(Request $request)
    {
        $rules = [
            'email' => 'required|email:rfc|max:255|unique:users,email,NULL,id,deleted_at,NULL',
        ];

        $messages = [
            'email.required' => 'Необходимо указать "E-mail"',
            'email.unique' => 'Пользователь с указанным E-mail адресом уже существует',
        ];

        $this->validate($request, $rules, $messages);

        return response()->json([
            'success' => true,
            'message' => '',
        ], 200);

    }

    public function register(Request $request)
    {
        $rules = [
            'employee' => 'required',
            'workplace' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'required',
            'phone' => 'required',
            'email' => 'required|email:rfc|max:255|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'required|string|min:6|confirmed',
        ];

        $messages = [
            'employee.required' => 'Необходимо указать, являетесь ли Вы сотрудником или нет',
            'workplace.required' => 'Необходимо заполнить поле "Место работы"',
            'first_name.required' => 'Необходимо заполнить поле "Имя"',
            'last_name.required' => 'Необходимо заполнить поле "Фамилия"',
            'middle_name.required' => 'Необходимо заполнить поле "Отчество"',
            'phone.required' => 'Необходимо заполнить поле "Телефон"',
            'email.required' => 'Необходимо заполнить поле "E-mail"',
            'email.unique' => 'Пользователь с указанным E-mail адресом уже существует',
            'password.required' => 'Необходимо заполнить поле "Пароль"',
            'password.min' => 'Пароль должен состоять не менее, чем из :min символов',
            'password_confirmation.required' => 'Необходимо заполнить поле "Пароль"',
        ];

        $this->validate($request, $rules, $messages);

        $UsersFields = UserField::all();

        $user = User::create([
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'api_token' => \Illuminate\Support\Str::random(60)
        ]);

        if( $request->filled('referal') )
        {
            $user->update([
                'referal' => $request->input('referal')
            ]);
        }

        if( $request->filled('first_name') )
        {
            $field = $UsersFields->where('slug', 'first_name')->first();

            $user->fields()->attach($field->id, [
                'value' => $request->input('first_name'),
                'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                'is_show' => 0
            ]);
        }

        if( $request->filled('last_name') )
        {
            $field = $UsersFields->where('slug', 'last_name')->first();

            $user->fields()->attach($field->id, [
                'value' => $request->input('last_name'),
                'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                'is_show' => 0
            ]);
        }

        if( $request->filled('middle_name') )
        {
            $field = $UsersFields->where('slug', 'middle_name')->first();

            $user->fields()->attach($field->id, [
                'value' => $request->input('middle_name'),
                'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                'is_show' => 0
            ]);
        }

        if( $request->filled('phone') )
        {
            $field = $UsersFields->where('slug', 'telephone')->first();

            $user->fields()->attach($field->id, [
                'value' => $request->input('phone'),
                'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                'is_show' => 0
            ]);
        }

        if( $request->filled('employee') )
        {
            switch($request->input('employee'))
            {
                case 1:
                    if( $request->input('workplace') <> '0' && $request->input('workplace') <> 0 )
                    {
                        $organization = Organization::where('id', (int) $request->input('workplace'))->orWhere('name', $request->input('workplace'))->orWhere('abbreviation', $request->input('workplace'));
                        $user->job()->create([
                            'organization_id' => $organization->count() ? $organization->first()->id : null,
                            'raw_organization' => $request->input('workplace')
                        ]);
                    }
                    $user->givePermissionTo(\Spatie\Permission\Models\Permission::where('name', 'internal')->get());
                break;
                case 0:
                default:
                    if( !is_int($request->input('workplace')) || $request->input('workplace') <> 0 )
                    {
                        $user->job()->create([
                            'raw_organization' => $request->input('workplace')
                        ]); 
                    }
                break;
            }
        }
        
        event(new \Illuminate\Auth\Events\Registered($user));

        Auth::login($user, true);
        
        if( $request->ajax() )
        {
            return response()->json([
                'success' => true,
                'message' => '',
                'data' => $user
            ], 200);
        }

        return route('user.index');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, $rules = [
            'employee' => 'required',
            'workplace' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'required',
            'phone' => 'required',
            'email' => 'required|email:rfc|max:255|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'required|string|min:6|confirmed',
        ], $messages = [
            'employee.required' => 'Необходимо указать, являетесь ли Вы сотрудником или нет',
            'workplace.required' => 'Необходимо заполнить поле "Место работы"',
            'first_name.required' => 'Необходимо заполнить поле "Имя"',
            'last_name.required' => 'Необходимо заполнить поле "Фамилия"',
            'middle_name.required' => 'Необходимо заполнить поле "Отчество"',
            'phone.required' => 'Необходимо заполнить поле "Телефон"',
            'email.required' => 'Необходимо заполнить поле "E-mail"',
            'email.unique' => 'Пользователь с указанным E-mail адресом уже существует',
            'password.required' => 'Необходимо заполнить поле "Пароль"',
            'password.min' => 'Пароль должен состоять не менее, чем из :min символов',
            'password_confirmation.required' => 'Необходимо заполнить поле "Пароль"',
        ]);
    }

    protected function create(array $data)
    {

        $UsersFields = UserField::all();

        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'referal' => !empty($data['referal']) ? $data['referal'] : null,
        ]);

        if( !empty($data['first_name']) )
        {
            $field = $UsersFields->where('slug', 'first_name')->first();

            $user->fields()->attach($field->id, [
                'value' => $data['first_name'],
                'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                'is_show' => 0
            ]);
        }

        if( !empty($data['last_name']) )
        {
            $field = $UsersFields->where('slug', 'last_name')->first();

            $user->fields()->attach($field->id, [
                'value' => $data['last_name'],
                'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                'is_show' => 0
            ]);
        }

        if( !empty($data['middle_name']) )
        {
            $field = $UsersFields->where('slug', 'middle_name')->first();

            $user->fields()->attach($field->id, [
                'value' => $data['middle_name'],
                'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                'is_show' => 0
            ]);
        }

        if( !empty($data['phone']) )
        {
            $field = $UsersFields->where('slug', 'telephone')->first();

            $user->fields()->attach($field->id, [
                'value' => $data['phone'],
                'points' => !empty($field->options) ? ( $field->options['points']['group'] == true ? ( $user->fields()->whereIn('field_id', $UsersFields->where('group_id', $field->group_id)->pluck('id'))->count() > 0 ? 0 : $field->options['points']['value'] ) : $field->options['points']['value'] ) : 0,
                'is_show' => 0
            ]);
        }

        if( !empty($data['employee']) )
        {
            switch($data['employee'])
            {
                case 1:
                    if( is_int($data['workplace']) )
                    {
                        if( $data['workplace'] > 0 )
                        {
                            $user->job()->create([
                                'organization_id' => Organization::where('id', $data['workplace'])->count() ? Organization::where('id', $data['workplace'])->first()->id : null,
                            ]);
                        }
                    }else{
                        if( $data['workplace'] <> 0 )
                        {
                            $user->job()->create([
                                'organization_id' => Organization::where('name', $data['workplace'])->orWhere('abbreviation', $data['workplace'])->count() ? Organization::where('name', $data['workplace'])->orWhere('abbreviation', $data['workplace'])->first()->id : null,
                                'raw_organization' => $data['workplace']
                            ]);
                        }
                    }
                    $user->givePermissionTo(\Spatie\Permission\Models\Permission::where('name', 'internal')->get());
                break;
                case 0:
                default:
                    if( !is_int($data['workplace']) || $data['workplace'] <> 0 )
                    {
                        $user->job()->create([
                            'raw_organization' => $data['workplace']
                        ]); 
                    }
                break;
            }
        }
        
        return $user;
    }
}
