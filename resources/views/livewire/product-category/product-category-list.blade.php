<div>


  @if ($modes['productCategoryProductList'])
    @livewire ('cafe-menu-product-category-product-list', ['productCategory' => $selectedProductCategory,])
  @else
    <div class="">

      @if ($products == null || count($products) == 0)

        <div class="table-responsive bg-white">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>Category</th>
                <th>Products</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($productCategories as $productCategory)
                <tr>
                  <td wire:click="$dispatch('displayProductCategory', { productCategory: {{ $productCategory }} } )" role="button">
                    <strong>
                      {{ $productCategory->name }}
                    </strong>
                  </td>
                  <td>
                    {{ count($productCategory->products) }}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  @endif


</div>
