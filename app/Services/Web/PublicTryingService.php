<?php

namespace App\Services\Web;

use App\Jobs\GenerateTryingUserCard;
use App\Mail\EmailNewTrialUser;
use App\Models\User;
use App\Repositories\Interfaces\RecordTypeRepositoryInterface;
use App\Repositories\Interfaces\TryingRecordRepositoryInterface;
use App\Repositories\Interfaces\TryingUserRepositoryInterface;
use App\Services\Traits\SaveRecordTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Null_;

class PublicTryingService
{
    use SaveRecordTrait;
    private $recordTypeRepository;
    private $tryingUserRepository;
    private $tryingRecordRepository;

    public function __construct(RecordTypeRepositoryInterface $recordTypeRepository, TryingUserRepositoryInterface $tryingUserRepository, TryingRecordRepositoryInterface $tryingRecordRepository)
    {
        $this->recordTypeRepository = $recordTypeRepository;
        $this->tryingUserRepository = $tryingUserRepository;
        $this->tryingRecordRepository = $tryingRecordRepository;
    }
    public function getRecordTypes()
    {
        return [
            'personal' => $this->recordTypeRepository->all(true)->general()->personal()->get(),
            'corporate' => $this->recordTypeRepository->all(true)->general()->corporate()->get()
        ];
    }

    public function newTryingUser($request)
    {
        Log::info($request);
        $data = [
            'email' => $request->email,
            'image' => NULL,
            'video' => NULL,
        ];
        if($request->image_cropping){
            $image = $request->image_cropping;  // your base64 encoded
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = Carbon::now()->timestamp . '.' . 'png';
            $data['image'] = "uploads/trying/users/" . $imageName;
            Storage::disk('public')->put("uploads/trying/users/" . $imageName, base64_decode($image));
        }
        $message = 'Records Added and email sent Successfully, Please Check your spam/junk folder if you cannot find the email in your public folder';

        if($request->input('username') && ! empty($request->input('username')) && $request->input('username') != 'try-service'){
            $inviter = User::where('username', $request->username)->firstOrFail();
            $data['user_id'] = $inviter->id;
            $message = 'Records Added and email sent Successfully to you and your inviter, Please Check your spam/junk folder if you cannot find the email in your public folder';
        }
        if ($request->hasFile('video')) {
            $video = $request->video;
            $videoName =  Carbon::now()->timestamp . '.' . '.mp4';
            $data['video'] = "uploads/trying/users/" . $videoName;
            Storage::disk('public')->put( "uploads/trying/users/" . $videoName ,  file_get_contents($video) );
        }

        $user = $this->tryingUserRepository->create($data);

        $record = $this->tryingRecordRepository->create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'first_name_file' => $request->file('first_name_file')->store('uploads/trying/users', ['disk' => 'public']), #record
            'last_name' => $request->last_name,
            'last_name_file' => $request->file('last_name_file')->store('uploads/trying/users', ['disk' => 'public']) #record
        ]);

        $email = $user->email;

        Mail::send('mail.trying_services', ['user_id' => $user->id, 'first_name' => $record->first_name, 'expires_at' => $user->created_at->addDays(15)->format('Y-m-d')], function ($message) use ($email) {
            $message->to($email)
                ->subject("Welcome To Sound&tag Trial Service");
        });
        return $message;
    }
}
