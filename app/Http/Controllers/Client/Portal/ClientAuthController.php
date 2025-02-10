<?php

namespace App\Http\Controllers\Client\Portal;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Notifications\ClientPortalLoginLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientAuthController extends Controller
{
  public function showLogin()
  {
    return inertia('ClientPortal/Auth/Login');
  }

  public function sendLoginLink(Request $request)
  {
    $request->validate([
      'email' => 'required|email'
    ]);

    $client = Client::where('email', $request->email)
      ->whereNotNull('email_verified_at')
      ->first();

    if (!$client) {
      return back()->withErrors([
        'email' => 'We could not find a verified client with this email address.'
      ]);
    }

    $token = $client->generateLoginToken();
    $client->notify(new ClientPortalLoginLink($token));

    return back()->with('status', 'We have emailed you a login link!');
  }

  public function login(Request $request, $token)
  {
    $client = Client::where('login_token', $token)
      ->whereNotNull('email_verified_at')
      ->first();

    if (!$client || !$client->hasValidLoginToken()) {
      return redirect()->route('client-portal.login')
        ->withErrors(['email' => 'This login link is invalid or has expired.']);
    }

    // Clear the token and log the client in
    $client->login_token = null;
    $client->login_token_expires_at = null;
    $client->save();

    Auth::guard('client')->login($client);

    return redirect()->route('client-portal.dashboard');
  }

  public function logout(Request $request)
  {
    Auth::guard('client')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('client-portal.login');
  }
}
