<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function ($request, $next) {
            // dd($request->route()->parameter('shop')); //文字列
            // dd(Auth::id()); //数字

            $id = $request->route()->parameter('shop'); //shopのid取得
            if (!is_null($id)) { // null判定
                $shopsOwnerId = Shop::findOrFail($id)->owner->id;
                $shopId = (int)$shopsOwnerId; // キャスト 文字列→数値に型変換
                $ownerId = Auth::id();
                if ($shopId !== $ownerId) { // 同じでなかったら
                    abort(404); // 404画面表示
                }
            }
            return $next($request);
        });
    }

    public function index()
    {
        $ownerId = Auth::id();  //認証されているID
        $shops = Shop::where('owner_id', $ownerId)->get();  //検索条件を指定して、ログインしているOwnerのIDが入ったshopだけを引っ張ってこれる

        return view(
            'owner.shops.index',
            compact('shops')
        );
    }

    public function edit($id)
    {
        dd(Shop::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
    }
}
