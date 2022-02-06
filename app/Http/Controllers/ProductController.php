<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductModel;
use CoinbaseCommerce\ApiClient;
use CoinbaseCommerce\Resources\Checkout;

class ProductController extends Controller
{
    public function index() {
        $products = ProductModel::all();
        return view('welcome', compact('products'));
    }

    public function products() {
        $products = ProductModel::all();
        return view('products', compact('products'));
    }

    public function checkout( Request $request ) {
        $apikey = ApiClient::init(env('APP_COIN_API_KEY'));

        
        try {
            $name = $request->product_name;
            $desc = $request->product_desc;
            $price = $request->product_price;
            
            $checkoutObj = new Checkout([
                "description" => $desc,
                "local_price" => [
                    "amount" => $price,
                    "currency" => "USD"
                ],
                "name" => $name,
                "pricing_type" => "fixed_price",
                "requested_info" => ["email"]
            ]);
            try {
                $checkoutObj->save();

                ProductModel::create([
                    'name'  => $name,
                    'description'   => $desc,
                    'price' =>  $price,
                    'checkout_id'   =>  $checkoutObj->id,
                    'currency'  => 'USD'
                ]);

                return $checkoutObj->id;
                
            } catch (\Exception $exception) {
                return $exception->getMessage();
            }
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
       
    }

    public function update_product( Request $request ) {
        $apikey = ApiClient::init(env('APP_COIN_API_KEY'));

        try {

            $name = $request->product_name;
            $desc = $request->product_desc;
            $price = $request->product_price;
            $id = $request->product_id;

            $product = ProductModel::where( 'id' , $id )->first();
            
            $update = Checkout::updateById(
                $product->checkout_id,
                [
                    "description" => $desc,
                    "local_price" => [
                        "amount" => $price,
                        "currency" => "USD"
                    ],
                    "name" =>  $name,
                    "pricing_type" => "fixed_price",
                    "requested_info" => ["email"]
                ]
            );

            \DB::table('products')
            ->where( 'id' , $id )
            ->update([
                "name" => $name,
                "price" => $price,
                "description" => $desc
            ]);
            
            return $update;

        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
       
    }

    public function delete_product ( Request $request ) {
        $apikey = ApiClient::init(env('APP_COIN_API_KEY'));

        try {

            $id = $request->product_id;

            $product = ProductModel::where( 'id' , $id )->first();
            
            Checkout::deleteById($product->checkout_id);

            ProductModel::where( 'id' , $id )->delete();
            
            return $id;

        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function checkoutv2() {
        $apikey = ApiClient::init(env('APP_COIN_API_KEY'));

        $checkoutObj = new Checkout([
            "description" => "Mastering the Transition to the Information Age",
            "local_price" => [
                "amount" => "1.00",
                "currency" => "USD"
            ],
            "name" => "test item 15 edited",
            "pricing_type" => "fixed_price",
            "requested_info" => ["email"]
        ]);
        
        try {
            $checkoutObj->save();
            echo sprintf("Successfully created new checkout with id: %s \n", $checkoutObj->id.'<br><br>');
        } catch (\Exception $exception) {
            echo sprintf("Enable to create checkout. Error: %s \n", $exception->getMessage());
        }

        if ($checkoutObj->id) {
        
            $checkoutObj->name = "New name";
        
            // Update "name"
            try {
                $checkoutObj->save();
                echo sprintf("Successfully updated name of checkout via save method\n".'<br><br>');
            } catch (\Exception $exception) {
                echo sprintf("Enable to update name of checkout. Error: %s \n", $exception->getMessage());
            }
        
            // Update "name" by "id"
            try {
                Checkout::updateById(
                    $checkoutObj->id,
                    [
                        "name" => "Another Name"
                    ]
                );
                echo sprintf("Successfully updated name of checkout by id\n".'<br><br>');
            } catch (\Exception $exception) {
                echo sprintf("Enable to update name of checkout by id. Error: %s \n", $exception->getMessage());
            }
        
        
            $checkoutObj->description = "New description";
        
            // Refresh attributes to previous values
            try {
                $checkoutObj->refresh();
                echo sprintf("Successfully refreshed checkout\n".'<br><br>');
            } catch (\Exception $exception) {
                echo sprintf("Enable to refresh checkout. Error: %s \n", $exception->getMessage());
            }
        
            // Retrieve checkout by "id"
            try {
                $retrievedCheckout = Checkout::retrieve($checkoutObj->id);
                echo sprintf("Successfully retrieved checkout\n".'<br><br>');
                echo $retrievedCheckout;
            } catch (\Exception $exception) {
                echo sprintf("Enable to retrieve checkout. Error: %s \n", $exception->getMessage());
            }
        }
        
        try {
            $list = Checkout::getList(["limit" => 5]);
            echo sprintf("Successfully got list of checkouts\n".'<br><br>');
        
            if (count($list)) {
                echo sprintf("Checkouts in list:\n".'<br><br>');
        
                foreach ($list as $checkout) {
                    echo $checkout;
                }
            }
        
            echo sprintf("List's pagination:\n".'<br><br>');
            print_r($list->getPagination());
        
            echo sprintf("Number of all checkouts - %s \n", $list->countAll().'<br><br>');
        } catch (\Exception $exception) {
            echo sprintf("Enable to get list of checkouts. Error: %s \n", $exception->getMessage());
        }
        
        if (isset($list) && $list->hasNext()) {
            // Load next page with previous settings (limit=5)
            try {
                $list->loadNext();
                echo sprintf("Next page of checkouts: \n".'<br><br>');
                foreach ($list as $checkout) {
                    echo $checkout;
                }
            } catch (\Exception $exception) {
                echo sprintf("Enable to get new page of checkouts. Error: %s \n", $exception->getMessage());
            }
        }
        
        try {
            $allCharge = Checkout::getAll();
            echo sprintf("Successfully got all checkouts:\n".'<br><br>');
            
            foreach ($allCharge as $charge) {
                echo $charge;
            }
        } catch (\Exception $exception) {
            echo sprintf("Enable to get all checkouts. Error: %s \n", $exception->getMessage());
        }
    }
}
