<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Web\Trying\TryingUserRequest;
use App\Jobs\GenerateTryingUserCard;
use App\Jobs\GenerateTryingUserCardText;
use App\Models\TryingRecord;
use App\Models\TryingUser;
use App\Models\User;
use App\Services\Traits\SaveRecordTrait;
use App\Services\Web\PublicTryingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Throwable;

class PublicTryingController extends BaseWebController
{
    use SaveRecordTrait;

    private $publicTryingService;
    public function __construct(PublicTryingService $publicTryingService)
    {
        parent::__construct();
        $this->publicTryingService = $publicTryingService;
    }

    public function index($username = null)
    {
        try {
            $user = User::where('username', $username)->firstOrFail();
            $name = $user ? $user->full_name : "";
            $text = __('global.your_name_request_from', ['name' => $name]);
        } catch (\Exception $e) {
            $text = null;
        }
        return view('web.trying.index')->with('text', $text);
    }
    public function recordTypes()
    {
        return $this->publicTryingService->getRecordTypes();
    }
    public function getTryingUserProfile($user_id, Request $request)
    {
        $user = TryingUser::find($user_id, ['id', 'email', 'image', 'created_at']);

        if (!$user || $user->expire_date < Carbon::now()) {
            abort(404);
        }
        $record = TryingRecord::where('user_id', $user_id)->first(['first_name']);

        $page = __('global.profile_title', ['name' => $record->first_name]);
        // $desc = __('global.trying_profile_desc', ['name'=>$record->first_name]);
        $desc = "";
        $image = "uploads/profile/card-{$user->id}.jpg";
        $image = Storage::disk('public')->exists($image) ? storage_asset($image) : storage_asset($user->image);
        $video = "uploads/profile/card-{$user->id}.mp4";
        $video = Storage::disk('public')->exists($video) ? storage_asset($video) : storage_asset($user->video);
        SEOMeta::setTitle($page);
        SEOMeta::setDescription($desc);

        OpenGraph::setTitle($page);
        OpenGraph::setDescription($desc);
        OpenGraph::setUrl($request->fullUrl());
        OpenGraph::addImage($image, ['width' => 800, 'height' => 450]);

        TwitterCard::setTitle($page);
        TwitterCard::setUrl($request->fullUrl());
        TwitterCard::setDescription($desc);
        TwitterCard::addValue('image', $image);
        TwitterCard::setType("summary_large_image");
        TwitterCard::addImage($image);

        JsonLd::setTitle($page);
        JsonLd::setDescription($desc);
        JsonLd::addImage($image);

        return view('web.trying.profile', compact(['user', 'image', 'record','video']));
    }
    public function tryingUser(TryingUserRequest $request)
    {
        $message = $this->publicTryingService->newTryingUser($request);

        return Response::json([
            'status' => 'success',
            'message' => $message
        ]);
    }

    public function tryingUserProfile($user_id)
    {
        $data = [];

        $user = TryingUser::find($user_id);
        $user->image = $user->image ? storage_asset($user->image) : asset('storage/defaults/default-user.png');
        $data['user'] = $user;

        $data['records'] = TryingRecord::where('user_id', $user_id)->get(['first_name', 'first_name_meaning', 'last_name', 'last_name_meaning', 'first_name_file', 'last_name_file']);

        return Response::json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function unsubscribe($t_user_id, $t_user_email)
    {
        $user = TryingUser::where(['id'=> $t_user_id, 'email'=> $t_user_email])->firstOrFail();
        return view('web.trying.unsubscribe-trying-user', compact('t_user_id', 't_user_email'));
    }

    public function unsubscribeTryingUser($t_user_id, $t_user_email)
    {
        $user = TryingUser::where(['id'=> $t_user_id, 'email'=> $t_user_email])->firstOrFail();
        $user->delete();
        session()->flash('success',  __('global.unsubscribed_successfully'));
        return redirect('/');
    }
}
