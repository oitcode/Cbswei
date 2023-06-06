<div class="p-3">
  <div class="mb-3">
    <div class="font-weight-bold">
      Add product Specification
    </div>
  </div>
  <div class="form-group">
    <label>Keyword</label>
    <input type="text" class="form-control" wire:model.defer="keyword">
    @error ('keyword')
      <div class="text-danger">
        <i class="fas fa-exclamation-circle mr-1"></i>
        {{ $message }}
      </div>
    @enderror
  </div>
  <div class="form-group">
    <label>Value</label>
    <input type="text" class="form-control" wire:model.defer="value">
    @error ('value')
      <div class="text-danger">
        <i class="fas fa-exclamation-circle mr-1"></i>
        {{ $message }}
      </div>
    @enderror
  </div>

  <div class="my-3">
    <button class="btn btn-success mr-2" wire:click="store">
      Save
    </button>
    <button class="btn btn-danger mr-2"wire:click="$emit('productEditAddProductSpecificationModeCancelled')">
      Cancel
    </button>
  </div>
</div>
