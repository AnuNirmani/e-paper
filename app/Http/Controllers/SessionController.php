<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SessionController extends Controller
{
    /**
     * Revoke other active sessions for the authenticated user.
     */
    public function destroyOthers(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        $user = $request->user();

        // Invalidate other devices' sessions
        Auth::logoutOtherDevices($request->password);

        // Explicitly remove other DB-backed sessions if using database driver
        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))
                ->where('user_id', $user->getAuthIdentifier())
                ->where('id', '!=', $request->session()->getId())
                ->delete();
        }

        // Regenerate current session to prevent fixation
        $request->session()->regenerate();

        return back()->with('status', 'other-sessions-terminated');
    }
}
