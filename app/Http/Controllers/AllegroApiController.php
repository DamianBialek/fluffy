<?php


namespace App\Http\Controllers;


use App\Allegro\Api;
use App\OrderPosition;
use Illuminate\Http\Request;

class AllegroApiController extends Controller
{
    public function search(Request $request, $orderPositionId)
    {
        $orderPos = OrderPosition::find($orderPositionId);

        if(!$orderPos) {
            return $this->error([], 'Data not found', 404);
        }

        $user = auth("api")->user();

        $allegro = new Api($user->getAdditionalToken("allegro"));

        $data = $allegro->offersListing($orderPos->name, $request->get("offset", 0), $request->get("limit", 15));

        if($allegro->isAccessTokenRefreshed()) {
            $user->addAdditionalTokens("allegro", $allegro->getAccessTokenObject());
            $user->save();
        }

        return $this->success($data);
    }
}
