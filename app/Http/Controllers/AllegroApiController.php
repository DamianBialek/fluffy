<?php


namespace App\Http\Controllers;


use App\Allegro\Api;
use App\OrderPosition;
use Illuminate\Http\Request;

class AllegroApiController extends Controller
{
    public function search(Request $request)
    {
        $searchingPhrase = $request->get("phrase");

        if(empty($searchingPhrase)) {
            return $this->error([], 'Data not found', 404);
        }

        try {
            $user = auth("api")->user();
            $allegro = new Api($user->getAdditionalToken("allegro"));

            if($allegro->isAccessTokenRefreshed()) {
                $user->addAdditionalTokens("allegro", $allegro->getAccessTokenObject());
                $user->save();
            }

            $data = $allegro->offersListing($searchingPhrase, $request->get("offset", 0), $request->get("limit", 15));


            return $this->success(array_merge($data, ["searchingPhrase" => $searchingPhrase]));
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }
}
