<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::query()
        ->when($request->has('username'), 
            fn ($query)=>$query->where('username', 'like', '%'. $request->input('username').'%'))
        
            ->when($request->has('email'), 
            fn ($query)=>$query->where('email', 'like', '%'. $request->input('email').'%'))

            ->when($request->query('trashed')==='true',function($query){
                $query->onlyTrashed();
            })
        ->get();        
        
        return UserResource::collection($users);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Str::random(8); // Le colocamos una contraseña por defecto

        if (!isset($data['hiring_date'])){
            $date['hiring_date']=now();
        }

        $user = User::create($data);
        
        return response()->json(UserResource::make($user), 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return UserResource::make($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUserRequest $request, User $user)
    {
        $user->update($request->validated());
        return UserResource::make($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        //Returning 204 no content response for a successful deletion is a standard practice
        return response()->noContent();
    }

    public function restore($id){
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return response()->json([
            "message"=>"Usuario restaurado correctamente."
        ]);

    }
}
