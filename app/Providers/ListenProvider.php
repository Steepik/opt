<?php

namespace App\Providers;

use App\Comment;

use App\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class ListenProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // if new comment was created notify user
        Comment::created(function($comment){
            $order = Order::find($comment->oid);
            if(Auth::user()->id != $order->uid) {
                $order->commented = true;
                $order->save();
            }
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
