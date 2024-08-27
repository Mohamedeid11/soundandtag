<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\CreateUser;
use App\Http\Requests\Admin\Users\DeleteUser;
use App\Http\Requests\Admin\Users\EditUser;
use App\Http\Requests\Admin\Users\FakePaymentRequest;
use App\Models\Family;
use App\Models\LoginAttempt;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\TryingUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class UsersController extends BaseAdminController
{
    /**
     * UsersController constructor.
     * Authorize requests using App\Policies\Admin\UserPolicy.
     */
    public function __construct()
    {
        parent::__construct(User::class);
        $this->authorizeResource(User::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(100);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User;
        return view('admin.users.create-edit', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUser $request)
    {
        $data = $request->only([
            'country_id', 'username', 'email', 'image',
            'phone', 'hidden', 'featured', 'account_type', 'active', 'name', 'website'
        ]);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads/profile', ['disk' => 'public']);
        } else {
            $default = $request->account_type === 'personal' ? 'default-user.png' : 'default-corporate.png';
            $image = "uploads/profile/" . time() . ".png";
            Storage::disk('public')->copy("defaults/" . $default, $image);
            $data['image'] = $image;
        }
        $data['active'] = $request->filled('active');
        $data['featured'] = $request->filled('featured');
        $data['hidden'] = $request->filled('hidden');
        User::create($data);
        $request->session()->flash('success', __('admin.success_add', ['thing' => __('global.user')]));
        return redirect(route('admin.users.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $records = $user->records()->paginate(40);
        $payment = new Payment;
        $hits = [];
        foreach (range(1, 12) as $month) {
            $this_year = $user->hits()->whereMonth('day', $month)->sum('count');
            $last_year = $user->hits()->whereYear('day', ((int) date('Y')) - 1)->whereMonth('day', $month)->sum('count');
            $hits[] = ["y" => "$month", 'a' => $this_year, 'b' => $last_year];
        }
        $hits = json_encode($hits);
        return view('admin.users.show', compact('records', 'user', 'payment', 'hits'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.users.create-edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditUser $request
     * @param User $user
     * @return void
     */
    public function update(EditUser $request, User $user)
    {
        $data = $request->only([
            'country_id', 'username', 'email', 'image',
            'phone', 'hidden', 'featured', 'account_type', 'active', 'name', 'website'
        ]);
        $data['active'] = $request->filled('active');
        $data['featured'] = $request->filled('featured');
        $data['hidden'] = $request->filled('hidden');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($user->image);
            $data['image'] = $request->file('image')->store('uploads/profile', ['disk' => 'public']);
        }
        $user->update($data);
        $request->session()->flash('success', __('admin.success_edit', ['thing' => __('global.user')]));
        return redirect(route('admin.users.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteUser $request
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(DeleteUser $request, User $user)
    {
        $user->delete();
        $request->session()->flash('success',  __('admin.success_delete', ['thing' => __('global.user')]));
        return redirect(route('admin.users.index'));
    }
    /**
     * Batch remove specified resources from storage
     *
     * @param DeleteFamily $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function batchDestroy(DeleteUser $request)
    {
        $ids = json_decode($request->input('bulk_delete'), true);
        $target_users = User::whereIn('id', $ids);
        $target_users->delete();
        $request->session()->flash('success',  __('admin.success_delete', ['thing' => __('global.user')]));
        return redirect(route('admin.users.index'));
    }

    /**
     *
     * @param $user_id
     * @return false|string
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function toggle_active($user_id)
    {
        $this->authorize('viewAny', User::class);
        $user = User::findOrFail($user_id);
        $user->active = !$user->active;
        $user->save();
        return json_encode([
            'status' => 0,
            'message' => __("admin.status_success")
        ]);
    }
    public function toggleFeatured($user_id)
    {
        $this->authorize('viewAny', User::class);
        $user = User::findOrFail($user_id);
        $user->featured = !$user->featured;
        $user->save();
        return json_encode([
            'status' => 0,
            'message' => __("admin.status_success")
        ]);
    }
    public function fakePayment(FakePaymentRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $data = $request->only(['plan_id', 'payment_type', 'value']);
        $plan = Plan::find($data['plan_id']);
        $data['confirmed'] = $request->filled('confirmed');
        $data['transaction_id'] = "FAKE" . date('Y-m-d hh:ii:ss');
        $data['start_date'] = Carbon::now();
        $data['end_date'] = Carbon::now()->addYears($plan->years);
        $payment = Payment::create([
            'user_id' => $user->id, 'transaction_id' => $data['transaction_id'], 'payment_type' => $data['payment_type'],
            'confirmed' => $data['confirmed'], 'value' => $data['value']
        ]);
        $user->user_plans()->create(['plan_id' => $data['plan_id'], 'payment_id' => $payment->id, 'start_date' => $data['start_date'], 'end_date' => $data['end_date']]);
        session()->flash('success',  __('admin.success_add', ['thing' => __('global.payment')]));
        return redirect(route('admin.users.show', ['user' => $user->id]));
    }
    public function impersonate($user_id)
    {
        $user = User::findOrFail($user_id);
        $this->authorize('impersonate', $user);
        Auth::guard('user')->logout();
        session()->invalidate();
        session()->regenerateToken();
        Auth::guard('user')->login($user, true);
        session()->regenerate();
        Session::put('web_auth_token', Auth::guard('user')->user()->createToken('token')->plainTextToken);
        return redirect()->intended(route('web.get_landing'));
    }

    public function listTrialUsers()
    {
        $trial_users = TryingUser::paginate(100);
        return view('admin.trial_users.index', compact('trial_users'));
    }

    public function deleteTrialUser(DeleteUser $request, TryingUser $user)
    {
        $user->delete();
        $request->session()->flash('success',  __('admin.success_delete', ['thing' => __('global.user')]));
        return redirect(route('admin.trial_users'));
    }

    public function loginAttempts()
    {
        $loginAttempts = LoginAttempt::orderBy('created_at', 'desc')->paginate(100);
        return view('admin.login_attempts.index', compact('loginAttempts'));
    }
}
