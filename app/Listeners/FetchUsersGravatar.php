<?php

namespace App\Listeners;

use App\Http\Curl;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\CurlRequestException;

class FetchUsersGravatar
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered $event
     * @return void
     * @throws \Throwable
     */
    public function handle(Registered $event)
    {
        $email = md5(strtolower($event->user->email));

        $url = sprintf('https://www.gravatar.com/avatar/%s?s=100&d=404&r=r', $email);

        try {
            $gravatar = Curl::get($url);
            $filename = sprintf('%s.jpg', $event->user->id);

            Storage::disk('avatars')->put($filename, $gravatar);

            $event->user->update(['picture' => $filename]);
        } catch (CurlRequestException $e) {
            return null;
        }
    }
}
