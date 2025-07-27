<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFIleUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class Avatarupload extends Component
{
    use WithFileUploads; 
    public $avatar;

    public function save() 
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        $manager = new ImageManager(new Driver());
        $image = $manager->read($this->avatar);
        $imgData = $image->cover(120, 120)->toJpeg();

        $user = Auth::user();
        $filename =  $user->id . "-" . uniqid() . ".jpg";
        Storage::disk('public')->put('avatars/' . $filename, $imgData);

        $oldAvatar = $user->avatar;

        $user->avatar = $filename;
        $user->save();

        if ($oldAvatar && $oldAvatar != '/fallback-avatar.jpg') {
            $relativePath = str_replace(asset('storage/'), '', $oldAvatar);
            Storage::disk('public')->delete($relativePath);
        }

        session()->flash('success', 'Congrats on the new avatar.');
        return $this->redirect('/manage-avatar', navigate: true);
    }

    public function render()
    {
        return view('livewire.avatarupload');
    }
}
