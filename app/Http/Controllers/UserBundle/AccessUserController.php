<?php

namespace App\Http\Controllers\UserBundle;

use App\Http\Controllers\MYBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Model\User;

class AccessUserController extends MYBaseController
{
 
     public function __construct(){

        $this->setCaption(\App\Http\Model\SubMenu::where("code","access-user")->first());

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $param = $request->all();
        
        $users = User::paginate(20);
        if(isset($param['q'])) {
            if($param['q']) $users = User::orWhereRaw('LOWER(name) LIKE ?', ['%' . strtolower($param['q']) . '%'])->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($param['q']) . '%'])->paginate(20);
        }else{
            $param['q'] = '';
        }
        return $this->view('access-users.index',compact('users','param'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return $this->view('access-users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->all();
        $message = "User add Failed";
        if( ($data['password'] && $data['password_confirmation'] ) && ($data['password'] === $data['password_confirmation']) ) {

            $this->validator($data)->validate(); 
            $users = User::create([
                'name' => $data['fullname'],
                'email' => $data['email'],
                'fullname' => $data['fullname'],
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
                'role_code' => $data['role_code'],
                'active' => $data['active'],
            ]);
            if( $users->id ) {
                 $message = 'User added successfully';
                return redirect()->route('access-users.edit',$users->id)->with(['message' => $message ]);
            } 
           
        }
         return redirect()->route('access-users.index')->with(['message' =>$message ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $users = User::findOrFail($id);
        $menus = \App\Http\Model\Menu::all();
        return  $this->view('access-users.edit',compact('users','menus' ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $msg = "failed update user";
            $user = User::findOrFail($id);
            $user->name = $data['fullname'];
            $user->email = $data['email'];
            $user->fullname = $data['fullname'];
            $user->role_code = $data['role_code'];
            $user->active = $data['active'];
            if( ($data['password'] && $data['password_confirmation'] ) && ($data['password'] === $data['password_confirmation']) ) {
                $user->password =  Hash::make($data['password']);
            }

            if($user->save()){
                $msg = "success update user";
            }
        return redirect()->route('access-users.edit',$id)->with(['message' => $msg ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $users = User::findOrFail($id);
        $users->delete();
        return redirect()->route('access-users.index')->with(['message' => 'User deleted successfully']);
    }
}
