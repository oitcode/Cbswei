<div>
  <div class="form-group">
    <label>Heading</label>
    <input type="text" class="form-control" wire:model.live="heading">
  </div>

  <div class="">
    <button class="btn btn-success" wire:click="store">
      Save
    </button>
    <button class="btn btn-danger" wire:click="$dispatch('webpageContentCreateHeadingCancelled')">
      Cancel
    </button>
  </div>
</div>
