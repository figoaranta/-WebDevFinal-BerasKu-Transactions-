<?php
namespace App\Http\Controllers\Api\v1;
use App\Http\Controllers\Controller;
use Cartalyst\Stripe\Laravel\Facades\ Stripe;
use Illuminate\Http\Request;
use App\Order;
use Session;
use App\Cart;
use Auth;

\Stripe\Stripe::setApiKey('sk_test_Z5nyrQe93T2hIZdrX7A1bPHG003aB4x50Q');

class CheckoutController extends Controller
{
    public function store(Request $request,$id)
    {
    	// dd($request->all());

        // try {
        //     $user = auth()->userOrFail();
        // } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
        //     return response()->json(['error'=> $e->getMessage()]);
        // }
        $oldCart = DB::table('carts')->where('id', $id)->first();
        $output = DB::table('carts')->where('id', $id);

        // $oldCart = Session::get('cart'.$id);
        $cart = new Cart($oldCart);

    	try {
    		$customer = \Stripe\Customer::create([
	            'name' => $request->name,
	            'source' => $request->stripeToken,
	            'email' => $request->email,
	            // 'phone' => $request->phone,
	            'description' => $request->description,
        	]);

    		$charge = Stripe::charges()->create([
    			'amount' => 500,
    			'currency' => 'usd',
                'customer' => $customer->id,
    			'description' => $request->description,
    			// 'receipt_email' => $request->email,
    			// 'metadata'=>[
                //     'contents' => 'MacBook Pro 2020',
                //     'quantity' => 1
    			// ],
    		]);

            // $order = Order::create([
            //     'cart' => serialize("Macbook 2020"),
            //     'address' => $request->input('address'),
            //     'name' => $request->input('name'),
            //     'paymentId' => $customer->id,
            //     'accountId' => $user->id,       <-- to use this, change column from account_id to accountId
            // ]);

            // $user = Account::find($id);
            $order = Order::create([
            	'account_id' => $id,
            	'cart' => serialize($cart),
            	'address' => $request->input('address'),
            	'name' => $request->input('name'),
            	'paymentId' => $customer->id
            ]);
            // $order->account_id = $id
            // $order->cart = serialize($cart);
            // $order->address = $request->input('address');
            // $order->name = $request->input('name');
            // $order->paymentId = $customer->id;
            // $user->orders()->save($order); 

            // orders()->save($order); 

            // Session::forget('cart'.$id);
            $output->delete();
    		return response()->json(['message'=>'Thank you! your payment has been successfully accepted!']);
    	} catch (\Stripe\Exception\CardException $e) {
    		return back()->withErrors('Error! ' . $e->getMessage());
    	}

    }

}
