@extends('users.invoice_layout')

@section('user_content')

	<div class="card shadow mb-4 table-card">
	    <div class="card-header py-3">
	      <h6 class="m-0 font-weight-bold text-primary">Purchase Invoice Details</h6>
	    </div>
	    
	    <div class="card-body">
	    	<div class="row clearfix justify-content-md-center">
	    		<div class="col-md-6">
                    <div class="no_padding"> <strong>Supplier:</strong>{{ $user->name }}</div>
                    <div class="no_padding"><strong>Email:</strong> {{ $user->email }}</div>
                    <div class="no_padding"><strong>Phone:</strong> {{ $user->phone }}</div>
                </div>
          <div class="col-md-3"></div>
	    		<div class="col-md-3">
                    <div><strong>Date:</strong>{{$invoice->date}}</div>
                    <div><strong>Challan_No:</strong> {{$invoice->challan_no}}</div>
                </div>
	    	</div>
            <div class="invoice_items">
                <table class="table table-borderless">
                    <thead>
                        <th>Sl</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th class="text-right">Total</th>
                        <th class="text-right">-</th>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $key=>$item)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $item->product->title }}</td>
                                <td>{{ $item->price }}</td>
                                <td>{{ $item->quantity}}</td>
                                <td class="text-right">{{ $item->total}}</td>
                                <td class="text-right">
                                    <form method="POST"
                                    action=" {{ route('user.purchases.delete_item', ['id' => $user->id, 'invoice_id' => $invoice->id, 'item_id'=> $item->id]) }} ">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger btn-sm"> 
                                            <i class="fa fa-trash"></i>  
                                        </button>	
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tr>
                        <th></th>
                        <th>
                            <button class="btn btn-info" data-toggle="modal" data-target="#newProduct">
                                <i class="fa fa-plus"></i>Add Product
                            </button>
                        </th>
                        <th colspan="2" class="text-right">Total:</th>
                       <th class="text-right"> {{ $totalPayable }} </th>               
                        <th></th>
                      </tr>
                    <tr>
                      <th></th>
                      <th>
                          <button class="btn btn-primary" data-toggle="modal" data-target="#newpaymentForinvoice">
                              <i class="fa fa-plus"></i>Add Payment
                          </button>
                      </th>
                      <th colspan="2" class="text-right">Paid:</th>
                      <th class="text-right"> {{$totalPaid}} </th>
                      <th></th>
                  </tr>
                  <tr>
                    <th colspan="4" class="text-right">Due:</th>
                    <th class="text-right">{{ $totalPayable - $totalPaid }}</th>
                  </tr>
                </table>
            </div>
	    </div>

	</div>

    {{-- Modal For add new product --}}

	<!-- Modal -->
    <div class="modal fade" id="newProduct" tabindex="-1" role="dialog" aria-labelledby="newProductModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            {!! Form::open([ 'route' => ['user.purchases.add_item', ['id' => $user->id, 'invoice_id' => $invoice->id] ], 'method' => 'post' ]) !!}
		<div class="modal-content">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="newProductModalLabel">Add New Product </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">	
                        
                    <div class="form-group row">
                        <label for="product" class="col-sm-2 col-form-label">Products</label>
                        <div class="col-sm-10">
                            {{ Form::select('product_id', $products, NULL, [ 'class'=>'form-control', 'id' => 'product', 'placeholder' => 'Select Product' ]) }}
                        </div>
                    </div>
    
                    <div class="form-group row">
                      <label for="price" class="col-sm-3 col-form-label">Price <span class="text-danger">*</span>  </label>
                      <div class="col-sm-9">
                        {{ Form::text('price', NULL, [ 'class'=>'form-control', 'id' => 'price','onkeyup' => 'getTotal()', 'placeholder' => 'Price', 'required' ]) }}
                      </div>
                    </div>
  
                    <div class="form-group row">
                      <label for="quantity" class="col-sm-3 col-form-label">Quantity </label>
                      <div class="col-sm-9">
                        {{ Form::text('quantity', NULL, [ 'class'=>'form-control', 'id' => 'quantity','onkeyup' => 'getTotal()', 'rows' => '3', 'placeholder' => 'quantity' ]) }}
                      </div>
                    </div>
                    <div class="form-group row">
                        <label for="total" class="col-sm-3 col-form-label">Total </label>
                        <div class="col-sm-9">
                          {{ Form::text('total', NULL, [ 'class'=>'form-control', 'id' => 'total', 'rows' => '3', 'placeholder' => 'Total' ]) }}
                        </div>
                      </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Submit</button>	
            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>

  {{-- Modal For add new Receipt for invoice--}}

	<!-- Modal -->
	<div class="modal fade" id="newpaymentForinvoice" tabindex="-1" role="dialog" aria-labelledby="newpaymentForinvoice" aria-hidden="true">
		<div class="modal-dialog" role="document">
			{!! Form::open([ 'route' => [ 'user.payments.store', [$user->id, $invoice->id] ], 'method' => 'post' ]) !!}
		  <div class="modal-content">
			<div class="modal-header">
			  <h5 class="modal-title" id="newpaymentForinvoice"> New Payment For This Invoice </h5>
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			  </button>
			</div>
			<div class="modal-body">	
					
					<div class="form-group row">
					  <label for="date" class="col-sm-3 col-form-label"> Date <span class="text-danger">*</span> </label>
					  <div class="col-sm-9">
						{{ Form::date('date', NULL, [ 'class'=>'form-control', 'id' => 'date', 'placeholder' => 'Date', 'required' ]) }}
					  </div>
					</div>

					<div class="form-group row">
					  <label for="amount" class="col-sm-3 col-form-label">Amount <span class="text-danger">*</span>  </label>
					  <div class="col-sm-9">
						{{ Form::text('amount', NULL, [ 'class'=>'form-control', 'id' => 'amount', 'placeholder' => 'Amount', 'required' ]) }}
					  </div>
					</div>

					<div class="form-group row">
					  <label for="note" class="col-sm-3 col-form-label">Note </label>
					  <div class="col-sm-9">
						{{ Form::textarea('note', NULL, [ 'class'=>'form-control', 'id' => 'note', 'rows' => '3', 'placeholder' => 'Note' ]) }}
					  </div>
					</div>
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			  <button type="submit" class="btn btn-primary">Submit</button>	
			</div>
		  </div>
		  {!! Form::close() !!}
		</div>
	</div>

  <script>
    function getTotal() {
        var price 		= document.getElementById("price").value;
        var quantity 	= document.getElementById("quantity").value;
        if ( price && quantity ) {
          var total = price * quantity;
          document.getElementById("total").value = total;
        }
      }
  
  </script>
@stop