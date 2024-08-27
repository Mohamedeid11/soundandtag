<?php
namespace App\Services\Web;

use App\Models\CorporateEmployee;
use App\Repositories\Interfaces\RecordTypeRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Traits\SaveRecordTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AuthService {
    use SaveRecordTrait;

    protected $userRepository;
    private $recordTypeRepository;
    public function __construct(UserRepositoryInterface $userRepository, RecordTypeRepositoryInterface $recordTypeRepository)
    {
        $this->userRepository = $userRepository;
        $this->recordTypeRepository = $recordTypeRepository;
    }
    public function register($data, $plan = NULL): bool
    {
        $company = request() ? request()->input('company') : null;
        $hash = request() ? request()->input('hash') : null;
        $email = request() ? request()->input('email') : null;
        $inviter = request() ? request()->input('invited_by') : null;
        if ($company && $hash && $email){
            $company = $this->userRepository->all(true)->where(['id'=>$company])->first();
            if($company) {
                $hash = hash_equals($hash, sha1($company->email)) ? $hash : null;
                if(! $hash){
                    session()->flash('error',__('global.expired_link_ask_to_resend'));
                    return false;
                }
                else {
                    if(! in_array($data['email'], $company->employees->pluck('email')->toArray())){
                        session()->flash('error', __('global.email_not_registred_as_employee_for_company') );
                        return false;
                    }
                    //get category
                    $corporate_employee = CorporateEmployee::where('email', $email)->first();
                    $category_id = $corporate_employee ? $corporate_employee->category_id: null;

                    $data['category_id'] = $category_id;
                    $data['company_id'] = $company->id;
                    $data['email'] = $email;
                    $data['email_verified_at'] = now();
                }
            }
        }
        if ($inviter){
            $inviter = $this->userRepository->get($inviter);
            if ($inviter){
                $data['invited_by'] = $inviter->id;
            }
        }
        $data['password'] = Hash::make($data['password']);
        $data['plan_id'] = $plan;
        $default = ( $data['account_type'] === 'personal')? 'default-user.png' : (($data['account_type'] === 'employee') ? 'employee.png' : 'default-corporate.png');
        $image = "uploads/profile/".time().".png";
        Storage::disk('public')->copy("defaults/".$default, $image);
        $data['image'] = $image;
        /** @var User $user */
        // dd($data);
        $user = $this->userRepository->create(Arr::except($data, 'records'));
        $records = $data['records'] ?? "{}";
        $records = json_decode($records, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            foreach ($records as $record_type_id => $record) {
                $record_type = $this->recordTypeRepository->get($record_type_id);
                if ($record_type && $record_type->account_type == $user->account_type) {
                    if (Arr::has($record, 'text_representation') && Arr::has($record, 'record_data')) {
                        $record_file = $user->records()->where(['record_type_id' => $record_type_id])->first();
                        $this->deleteRecordFile($record_file);
                        $file_path = $this->saveRecordFile($record['record_data']);
                        $user->records()->updateOrCreate(
                            ['record_type_id' => $record_type_id],
                            ['text_representation' => $record['text_representation'],
                                'file_path' => $file_path]
                        );
                    }
                }
            }
        }
        $this->sendEmailVerification($user);
        event(new Registered($user));
//        $user->sendEmailVerificationNotification();
        return True;
    }
    public function sendEmailVerification($user){
        if(! $user->email_verified_at){
            $email = $user->email;
            Mail::send('mail.verification-email', ['user'=> $user],function($message) use ($email){
            $message->to($email)
                ->subject("Verify your email address on Sound&Tag");
            });
        }
    }
	public function getUser($username)
	{
	    return $this->userRepository->getBy('username', $username);
	}
}
