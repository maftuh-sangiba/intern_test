@extends('layouts.admin.app')

@section('content')

<div class="card">
  <div class="card-body">
    <form>
      @csrf
      <div class="form-group">
        <label for="product">Select Product:</label>
        <select id="product" class="form-control">
          <option value="">Select a product</option>
            @foreach ($produks as $item)
              <option value="{{ $item->product_price_sell }}"" data-id="{{ $item->id }}">{{ $item->product_name }}</option>
            @endforeach
        </select>
      </div>
      <div class="form-group">
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" class="form-control" min="1" value="0">
      </div>
      <button id="addProduct" type="button" class="btn btn-primary">Add Product</button>
      <hr>
      <h5>Selected Products:</h5>
        <table id="selectedProducts" class="table">
          <thead>
            <tr>
              <th>Product Id</th>
              <th>Product</th>
              <th>Quantity</th>
              <th>Price</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      <h5>Total Price: <span id="totalPrice">0</span></h5>
      <button class="btn btn-success btn-sm mt-3" id="submitData">Tambah Transaksi</button>
    </form>
  </div>
</div>

@endsection

@push('script')
<script>
  $(document).ready(function () {
    const addProductBtn = $('#addProduct');
    const productDropdown = $('#product');
    const quantityInput = $('#quantity');
    const selectedProductsTable = $('#selectedProducts');
    const totalPriceSpan = $('#totalPrice');

    addProductBtn.on('click', function () {
      const productId = productDropdown.find('option:selected').attr("data-id");
      const productPrice = productDropdown.val();
      const productName = productDropdown.find('option:selected').text();
      const quantity = parseInt(quantityInput.val());

      if (productId && quantity > 0) {
        let existingRow = selectedProductsTable.find(`tbody tr[data-id="${productId}"]`);
        if (existingRow.length) {
          existingRow.find('td:nth-child(3)').text(quantity);
          existingRow.find('td:nth-child(4)').text(calculatePrice(productPrice, quantity));
        } else {
          const newRow = `
            <tr data-id="${productId}">
              <td>${productId}</td>
              <td>${productName}</td>
              <td>${quantity}</td>
              <td>${calculatePrice(productPrice, quantity)}</td>
            </tr>
          `;
          selectedProductsTable.find('tbody').append(newRow);
        }
        updateTotalPrice();
      } else {
        alert('Please select a product and enter a valid quantity.');
      }
    });

    function calculatePrice(productPrice, quantity) {
      return (productPrice * quantity);
    }

    function updateTotalPrice() {
      let totalPrice = 0;
      selectedProductsTable.find('tbody tr').each(function () {
        const price = parseFloat($(this).find('td:nth-child(4)').text());
        totalPrice += price;
      });
      totalPriceSpan.text(totalPrice);
    }

    $('#submitData').on('click', function() {
      event.preventDefault();

      const rowCount = selectedProductsTable.find('tbody tr').length;

      if (rowCount === 0) {
        alert('Please add at least one product to submit.');
        return;
      }

      const selectedProducts = [];
      selectedProductsTable.find('tbody tr').each(function () {
        const productId = $(this).find('td:nth-child(1)').text();
        const productName = $(this).find('td:nth-child(2)').text();
        const quantity = parseInt($(this).find('td:nth-child(3)').text());
        const price = parseFloat($(this).find('td:nth-child(4)').text());
        
        selectedProducts.push({
          produk_id: productId,
          qty: quantity
        });
      });

      const totalPrice = parseFloat(totalPriceSpan.text());

      Swal.fire({
        title: 'Loading...',
        allowOutsideClick: false,
        onBeforeOpen: () => {
          Swal.showLoading();
        }
      });

      $.ajax({
        url: `{{ route('app.transaksi.store') }}`,
        method: 'POST',
        data: {
          _token: "{{ csrf_token() }}",
          selectedProducts: selectedProducts,
          total: totalPrice
        },
        success: function(response) {
          Swal.fire({
            title: 'Success',
            text: response.message,
            icon: 'success',
          }).then((result) => {
            window.location.href = `{{ route('app.transaksi.index') }}`;
          });
        },
        error: function(xhr, status, error) {
          Swal.fire({
            title: 'Error',
            text: xhr.responseJSON.message || 'Failed to create transaction.',
            icon: 'error',
          });
        }
      });

    });
  });
</script>
@endpush
