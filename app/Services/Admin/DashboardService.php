<?php
namespace App\Services\Admin;

use App\Repositories\Interfaces\ContactMessageRepositoryInterface;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashboardService
{
    private $userRepository;
    private $contactMessageRepository;
    private $subscriptionRepository;
    public function __construct(UserRepositoryInterface $userRepository, ContactMessageRepositoryInterface $contactMessageRepository, SubscriptionRepositoryInterface $subscriptionRepository)
    {
        $this->userRepository = $userRepository;
        $this->contactMessageRepository = $contactMessageRepository;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function getHealth()
    {
        // 0 good, 1 warning, 2 danger
        return collect(['status'=>0, 'message'=>__('admin.all_operational')]);
    }

    public function getUserStats()
    {
        return collect([
            'total' => $this->userRepository->all(true)->count(),
            'personal' => $this->userRepository->all(true)->personal()->count(),
            'corporate' => $this->userRepository->all(true)->corporate()->count(),
            'today' => collect(
                [
                    'total'=>$this->userRepository->all(true)->where('created_at', '>=', Carbon::now()->subDay())->count(),
                    'personal'=>$this->userRepository->all(true)->personal()->where('created_at', '>=', Carbon::now()->subDay())->count(),
                    'corporate'=>$this->userRepository->all(true)->corporate()->where('created_at', '>=', Carbon::now()->subDay())->count()
                ]
            ),
            'week' => collect(
                [
                    'total'=>$this->userRepository->all(true)->where('created_at', '>=', Carbon::now()->subWeek())->count(),
                    'personal'=>$this->userRepository->all(true)->personal()->where('created_at', '>=', Carbon::now()->subWeek())->count(),
                    'corporate'=>$this->userRepository->all(true)->corporate()->where('created_at', '>=', Carbon::now()->subWeek())->count()
                ]
            ),
            'month' => collect(
                [
                    'total'=>$this->userRepository->all(true)->where('created_at', '>=', Carbon::now()->subMonth())->count(),
                    'personal'=>$this->userRepository->all(true)->personal()->where('created_at', '>=', Carbon::now()->subMonth())->count(),
                    'corporate'=>$this->userRepository->all(true)->corporate()->where('created_at', '>=', Carbon::now()->subMonth())->count()
                ]
            ),
            'contact' => $this->contactMessageRepository->all(true)->count(),
            'subscription' => $this->subscriptionRepository->all(true)->count()
        ]);
    }

	public function updateProfile(array $data, array $files)
	{
	    $admin = auth()->guard('admin')->user();
        $data = Arr::only($data, ['name', 'password', 'image']);
        if(Arr::has($data, 'password') && $data['password'] != ''){
            $data['password'] = Hash::make($data['password']);
        }
        else {
            unset($data['password']);
        }
        if (Arr::has($files,'image')){
            Storage::disk('public')->delete($admin->image);
            $data['image'] = $files['image']->store('uploads/admins', ['disk'=>'public']);
        }
        $admin->update($data);
        session()->flash('success', __('admin.success_edit', ['thing'=>__('global.profile')]) );
    }
}
