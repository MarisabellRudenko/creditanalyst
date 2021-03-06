<?php
/*
* Управление пользователями с правами админа - создание пользователей, деактивация/удаление, управление привелегиями
*/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use App\User;

class User_management_Admin_Controller extends Controller
{
    
    /* Список всех пользователей и переход к созданию */
    public function index(){
        $users = User::paginate(5);

        return view('User_management_Admin.user_index', ['users' => $users]);
    }
    
    /* Создание пользователя : страница с формой */
    public function create_user_page(){
        return view('User_management_Admin.create_user_page');
    }

    /* Создание пользователя : обработка POST запроса */
    public function create_user_post(Request $request){
        // Создать пользователя с ролью "подписчик"
        $new_user = new User();
        $new_user->name = $request->first_name;
        $new_user->first_name = $request->first_name;
        $new_user->last_name = $request->last_name;
        $new_user->email = $request->email;
        $new_user->password = Hash::make($request->password);
        $new_user->role = 'user';
        $new_user->save();

        // ? Вернуться на страницу списка пользователей
        return redirect('/admin_user_management/index');
    }

    /* Редактирование пользователя : страница */
    public function edit_user_page($user_id){
        $user = User::find($user_id);
        return view('User_management_Admin.edit_user_page', ['user' => $user]);
    }

    /* Редактирование пользователя : обработка POST запроса */
    public function edit_user_post(Request $request){
        $user = User::find($request->user_id);
        $user->first_name = $request->first_name;
        $user->name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->save();
        return redirect('/admin_user_management/index');
    }

    /* Деактивировать пользователя */
    public function suspend_user($user_id){
        $user = User::find($user_id);
        $user->status = 'suspended';
        $user->save();

        return back();
    }

    /* Активировать пользователя */
    public function activate_user($user_id){
        $user = User::find($user_id);
        $user->status = 'active';
        $user->save();
        return back();
    }

    /* Сделать пользователя администратором */
    public function grant_admin_privileges($user_id){
        $user = User::find($user_id);
        $user->role = 'admin';
        $user->save();

        return back();
    }

    /* Забрать у пользователя администраторские права */
    public function remove_admin_privileges($user_id){
        $user = User::find($user_id);
        $user->role = 'user';
        $user->save();

        return back();
    }

}