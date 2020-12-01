<?php

namespace App\Http\Controllers;


use App\ShopifyTrigger;
use App\ShopifyWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\HandwryttenApi;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Log;

class ShopifyTriggerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $triggers = ShopifyTrigger::where('user_id', Auth::id())->latest()->get();
        $handwryttens = DB::table('handwrytten_apis')->where('user_id', '=', Auth::id())->get();

        return view('admin', compact('triggers', 'handwryttens'));

        // dd($triggers);

    }
    public function createview()
    {

        //    $shop = Auth::user();   
        //   $allwebhook = $shop->api()->rest('get', '/admin/api/2020-10/webhooks.json');
        //          $cusromers = $shop->api()->rest('get', '/admin/api/2020-10/customers/4312065802398.json')['body']['customer'];
        //            return  $cusromers;
        $handwrytten = DB::table('handwrytten_apis')->where('user_id', '=', Auth::id())->first();

        if (is_null($handwrytten)) {
            return back()->with('error', 'Please setup at least one active account of a Handwrytten');
        } else {

            $handwrytten = DB::table('handwrytten_apis')->where('user_id', '=', Auth::id())->first();
            $responseStyle = Http::withToken($handwrytten->token)->get('https://api.handwrytten.com/v1/fonts/list');
            $responseInsert = Http::withToken($handwrytten->token)->get('https://api.handwrytten.com/v1/inserts/list');
            $responseGiftCard = Http::withToken($handwrytten->token)->get('https://api.handwrytten.com/v1/giftCards/list');
            $responseCategory = Http::withToken($handwrytten->token)->get('https://api.handwrytten.com/v1/categories/list');
            $singlesteporder = Http::withToken($handwrytten->token)->post('https://api.handwrytten.com/v1/orders/singleStepOrder');
            $style = json_decode($responseStyle);
            $insertData = json_decode($responseInsert);
            $giftCard = json_decode($responseGiftCard);
            $category = json_decode($responseCategory);


            // return view('triggers', compact('trigger','handwrytten', 'style', 'insertData', 'giftCard', 'category'));
            return view('/triggers', compact('handwrytten', 'style', 'insertData', 'giftCard', 'category'));
        }
        // dd($triggers);

    }


    
    public function existtriggercheck(Request $check)
    {

        $trigger_name_selected = $check->trigger_name;
        $checkdata = $check->validate([
            'trigger_name'      => 'required|string|unique:shopify_triggers,trigger_name,NULL,id,user_id,' . Auth::id()

        ]);
       $handwrytten = DB::table('handwrytten_apis')->where('user_id', '=', Auth::id())->first();
        $responseStyle = Http::withToken($handwrytten->token)->get('https://api.handwrytten.com/v1/fonts/list');
        $responseInsert = Http::withToken($handwrytten->token)->get('https://api.handwrytten.com/v1/inserts/list');
        $responseGiftCard = Http::withToken($handwrytten->token)->get('https://api.handwrytten.com/v1/giftCards/list');
        $responseCategory = Http::withToken($handwrytten->token)->get('https://api.handwrytten.com/v1/categories/list');
        $singlesteporder = Http::withToken($handwrytten->token)->post('https://api.handwrytten.com/v1/orders/singleStepOrder');
        $style = json_decode($responseStyle);
        $insertData = json_decode($responseInsert);
        $giftCard = json_decode($responseGiftCard);
        $category = json_decode($responseCategory);


        // return view('triggers', compact('trigger','handwrytten', 'style', 'insertData', 'giftCard', 'category'));
    return view('/triggers', compact('trigger_name_selected', 'handwrytten', 'style', 'insertData', 'giftCard', 'category'));
     //   return redirect()->route('trigger.createview', compact('trigger', 'handwrytten', 'style', 'insertData', 'giftCard', 'category'));
       
    }


    public function store(Request $request)
    {
        //   $shop = Auth::user();   
        //   $allwebhook = $shop->api()->rest('get', '/admin/api/2020-10/webhooks.json');
        //   $cusromers = $shop->api()->rest('get', '/admin/api/2020-10/customers/4298225254558.json');
        //   return  $cusromers;

       

        $formData =     $request->validate([
            'trigger_name'      => 'required|string|unique:shopify_triggers,trigger_name,NULL,id,user_id,' . Auth::id(),
            'trigger_card' => 'required',
            'trigger_message' => 'required|string',
            'trigger_handwriting_style' => 'required|string'
        ]);

        $addTrigger = new ShopifyTrigger();
        $addTrigger->user_id = Auth::id();
        $addTrigger->trigger_name = $request->trigger_name;
        $enabled = '';
        if (empty($request->trigger_status)) {
            $enabled =  '0';
        } else {
            $enabled =  $request->trigger_status;
        }
        $addTrigger->trigger_status = $enabled;
        $addTrigger->card_id   = $request->card_id;
        $addTrigger->trigger_card = $request->trigger_card;
        $addTrigger->trigger_message = $request->trigger_message;
        $addTrigger->trigger_signoff = $request->trigger_signoff;
        $addTrigger->trigger_message = $request->trigger_message;
        $addTrigger->trigger_card_category = $request->trigger_card_category;
        $addTrigger->trigger_handwriting_style = $request->trigger_handwriting_style;
        $addTrigger->trigger_insert = $request->trigger_insert;
        $addTrigger->trigger_gift_card = $request->trigger_gift_card;

        $addTrigger->save();

        return redirect()->route('home')->with('success', 'Trigger  Saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ShopifyTrigger  $shopifyTrigger
     * @return \Illuminate\Http\Response
     */
    public function show(ShopifyTrigger $shopifyTrigger)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ShopifyTrigger  $shopifyTrigger
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $trigger = ShopifyTrigger::where('id', '=', $id)->first();
        $handwrytten = DB::table('handwrytten_apis')->where('user_id', '=', Auth::id())->first();
        $responseStyle = Http::withToken($handwrytten->token)->get('https://api.handwrytten.com/v1/fonts/list');
        $responseInsert = Http::withToken($handwrytten->token)->get('https://api.handwrytten.com/v1/inserts/list');
        $responseGiftCard = Http::withToken($handwrytten->token)->get('https://api.handwrytten.com/v1/giftCards/list');
        $responseCategory = Http::withToken($handwrytten->token)->get('https://api.handwrytten.com/v1/categories/list');
        $singlesteporder = Http::withToken($handwrytten->token)->post('https://api.handwrytten.com/v1/orders/singleStepOrder');
        $style = json_decode($responseStyle);
        $insertData = json_decode($responseInsert);
        $giftCard = json_decode($responseGiftCard);
        $category = json_decode($responseCategory);
        return view('triggers-edit', compact('trigger', 'handwrytten', 'style', 'insertData', 'giftCard', 'category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ShopifyTrigger  $shopifyTrigger
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $formData = $request->validate([
            'trigger_card' => '',
            'trigger_message' => 'required|string',
            'trigger_handwriting_style' => 'required|string'

        ]);

        $formData['user_id'] = Auth::id();
        $enabled = '';
        if (empty($request->trigger_status)) {
            $enabled =  '0';
        } else {
            $enabled =  $request->trigger_status;
        }
        $formData['trigger_status'] = $enabled;
        $formData['card_id'] = $request->card_id;
        $formData['trigger_card_category'] = $request->trigger_card_category;
        

        if ($request->trigger_card) {

            $formData['trigger_card'] = $request->trigger_card;
        } else {

            $formData['trigger_card'] = $request->old_trigger_card;
        }
        $formData['trigger_insert'] = $request->trigger_insert;
        $formData['trigger_gift_card'] = $request->trigger_gift_card;


        ShopifyTrigger::whereId($id)->update($formData);
        $triggers = ShopifyTrigger::where('user_id', Auth::id())->latest()->get();
        $handwryttens = DB::table('handwrytten_apis')->where('user_id', '=', Auth::id())->get();
        return redirect()->route('home')->with('success', 'Trigger  Saved');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ShopifyTrigger  $shopifyTrigger
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('shopify_triggers')->where('id', $id)->delete();
        return back()->with('delete', 'Trigger has been deleted');
    }
}
