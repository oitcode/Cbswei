<?php

namespace App\Http\Livewire\Cms\Dashboard;

use Livewire\Component;
use Livewire\WithFileUploads;

use App\GalleryImage;

class GalleryUpdate extends Component
{
    use WithFileUploads;

    public $gallery;

    public $name;
    public $description;
    public $comment;

    public $new_images = [];

    public function render()
    {
        $this->name = $this->gallery->name;
        $this->description = $this->gallery->description;
        $this->comment = $this->gallery->comment;

        return view('livewire.cms.dashboard.gallery-update');
    }

    public function update()
    {
        $validatedData = $this->validate([
            'name' => 'required',
            'new_images.*' => 'nullable|image',
        ]);

        $this->gallery->update($validatedData);

        foreach ($this->new_images as $image) {
            $imagePath = $image->store('gallery_image', 'public');

            $galleryImage = new GalleryImage;

            $galleryImage->gallery_id = $this->gallery->gallery_id;
            $galleryImage->image_path = $imagePath;

            $galleryImage->save();
        }

        $this->emit('exitUpdate');
    }

    public function deleteImageFromGallery(GalleryImage $galleryImage)
    {
        $galleryImage->delete();
        $this->gallery = $this->gallery->fresh();
        $this->render();
    }
}
