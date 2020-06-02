<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;                                                                                                                                

use App\User;

class UserController extends Controller
{

    public function index()
    {
        $users = User::orderBy('created_at','desc')->paginate(10);
        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'identity_id' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'photo' => 'required|image|mimes:jpg,jpeg,png',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
            'phone_number' => 'required',
            'role' => 'required',
            'status' => 'required'
        ]);

        $filename = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = Str::random(5) . $request->email .'.'. $file->getClientOriginalExtension();
            $file->move('images/users',$filename);
        }

        $data = User::create([
            'name' => $request->name,
            'identity_id' => $request->identity_id,
            'gender' => $request->gender,
            'address' => $request->address,
            'photo' => $filename,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'api_token' => $request->api_token,
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Success",
            "data" => $data
        ]);
    }

    public function show($id)
    {
        $user = User::where('id',$id)->first();

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'Success',
                'data' => $user
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Data user id '.$id.' Not found',
            'data' => null
        ],404);
    }

    public function update(Request $request, $id)
    {
        $user = User::where('id',$id)->first();

        if ($user) 
        {
            $filename = $user->photo;
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = $request->email ? 
                    Str::random(5). $request->email. '.' .$file->getClientOriginalExtension()            
                    : Str::random(5). $user->email. '.' .$file->getClientOriginalExtension();
                $file->move('images/users',$filename);
                unlink('images/users/'.$user->photo);
            }

            $user->name = $request->name ? $request->name : $user->name;
            $user->identity_id = $request->identity_id ? $request->identity_id : $user->identity_id;
            $user->gender = $request->gender ? $request->gender : $user->gender;
            $user->address = $request->address ? $request->address : $user->address;
            $user->photo = $filename;
            $user->email = $request->email ? $request->email : $user->email;
            $user->password = $request->password ? Hash::make($request->password) : $user->password;
            $user->phone_number = $request->phone_number ? $request->phone_number : $user->phone_number;
            $user->role = $request->role ? $request->role : $user->role;
            $user->status = $request->status ? $request->status : $user->status;
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Success Updated',
                'data' => $user
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data user id '.$id.' Not found',
            'data' => null
        ],404);

    }

    public function destroy($id){
        $user = User::where('id',$id)->first();
        if ($user) {
            unlink('images/users/'.$user->photo);
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'Success Deleted'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Data user id '.$id.' Not found'
        ],404);

    }

}
