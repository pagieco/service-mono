<?php

namespace App\Http\Controllers;

class BackendController
{
    /**
     * Render the backend view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke()
    {
        return view('backend');
    }
}
