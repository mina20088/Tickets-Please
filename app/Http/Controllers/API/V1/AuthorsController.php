<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\StoreUserRequest;
use App\Http\Requests\API\V1\UpdateUserRequest;
use App\Http\Resources\V1\UsersResource;
use App\Models\User;
use App\services\v1\AuthorService;
use Illuminate\Http\Request;

class AuthorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AuthorService $service)
    {
        return UsersResource::collection(User::filter($service)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(AuthorService $service,User $author)
    {
        return UsersResource::make(
            User::where('id', $author->id)->filter($service)->first());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $author)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $author)
    {
        //
    }
}
