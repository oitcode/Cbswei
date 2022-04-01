<div class="card mb-3 shadow @if ($seatTable->isBooked()) bg-danger-rm @else bg-success text-white @endif h-100"
    wire:click="$emit('displayWorkingSeatTable', {{ $seatTable->seat_table_id }})"
    @if ($seatTable->isBooked())
      style="background-color: orange;"
    @else
    @endif
    role="button"
>
  <div class="card-header @if ($seatTable->isBooked()) bg-danger-rm @else bg-success text-white @endif">
    <div class="float-left">
      <h2 class="badge" style="font-size: 1.7rem;">
        {{ $seatTable->name }}
      </h2>
    </div>
    <div class="float-right" style="font-size: 1.5rem;">
        @if ($seatTable->isBooked())
          Rs
          @php echo number_format( $seatTable->getCurrentBookingTotalAmount() ); @endphp
        @else
        @endif
    </div>
    <div wire:loading class="float-right" style="font-size: 1.5rem;">
      <span class="spinner-border text-white mr-3" role="status">
      </span>
    </div>
    <div class="clearfix">
    </div>

  </div>

  <div class="card-body p-0">
    @if ($seatTable->getCurrentBooking())
    <div class="row" style="margin: auto;">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table @if ($seatTable->isBooked()) bg-danger-rm @else bg-success @endif text-white-rm">
            <tr class="border-0" style="font-size: 1.3rem;">
              <td class="border-0">
                <i class="fas fa-clock mr-3"></i>
                Start time
              </td>
              <td class="font-weight-bold border-0">
                @if ($seatTable->getCurrentBooking())
                  {{ $seatTable->getCurrentBooking()->created_at->format('h:i') }}
                @else
                  NA
                @endif
              </td>
            </tr>
            <tr class="border-0" style="font-size: 1.3rem;">
              <td class="border-0">
                <i class="fas fa-shopping-cart mr-3"></i>
                Total items
              </td>
              <td class="font-weight-bold border-0">
                @if ($seatTable->isBooked())
                  {{ $seatTable->getCurrentBookingTotalItems() }}
                @else
                  NA
                @endif
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    @else
      <div class="d-flex flex-column justify-content-center p-3 h-100">

        @if (true)
        <div class="d-flex justify-content-between">
          <h2 class="h3 ml-4-rm">
            OPEN
          </h2>
        </div>
        @endif
          <div class="mr-4">
            <i class="fas fa-plus-circle fa-3x"></i>
          </div>

      </div>
    @endif

    @if (false)
    <div class="p-2">
      <button class="btn btn-danger mr-3">
        <i class="fas fa-check mr-3"></i>
        Clear table
      </button>

      <button class="btn btn-success mr-3">
        Book
      </button>
    </div>
    @endif

  </div>
</div>
