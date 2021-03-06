<?php
namespace App\Controller\API;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Controller\BaseController;

use App\Models\Region\Area;
use App\Models\Region\Prefecture;
use App\Models\Region\City;

/**
 * Control a GeoJSON for area, prefecture, city in Japan.
 * Class GeoController
 * @package App\Controller\API
 */
class GeoController extends BaseController{
    public function getArea(Request $request, Response $response, $args){
        $params = $request->getAttribute('params');
        $areas = Area::query()->get(['id', 'name']);
        return $response->withJson($areas);
    }

    public function getPrefecture(Request $request, Response $response, $args){
        $params = $request->getAttribute('params');
        $area = Area::find($params->get->area_id);
        $prefectures = $area->prefectures()->get(['id', 'name']);
        return $response->withJson($prefectures);
    }

    public function getCity(Request $request, Response $response, $args){
        $params = $request->getAttribute('params');
        $prefecture = Prefecture::find($params->get->prefecture_id);
        $cities = $prefecture->cities()->get(['id', 'name']);
        return $response->withJson($cities);
    }

    public function getGeoJson(Request $request, Response $response, $args){
        $params = $request->getAttribute('params');
        $paths = [];
        // Enable Multiple params
        foreach(explode(',', $params->get->city_ids) as $key => $id){
            $city = City::find($id);
            if(!file_exists($city->path))
                continue;
            $paths[] = [
                'name' => $city->name,
                'path' => file_get_contents($city->path)
            ];
        }
        return $response->withJson($paths);
    }

    public function postCity(Request $request, Response $response, $args){
        $params = $request->getAttribute('params');
        $prefecture = Prefecture::findOrFail($params->post->prefecture_id);

        $pCode = ($prefecture->id < 10) ? preg_replace("/( |　)/", "", '0' . (string)$prefecture->id): (string)$prefecture->id;

        $city = new City();
        $city->fill([
           'name' => $params->post->name,
           'name_en' => $params->post->name_en,
           'code' => $params->post->code,
           'path' => $params->path->public_path . 'geojson/' . $pCode . '/' . $params->post->code . '.json',
        ]);

        if($prefecture->cities()->save($city)){
            $this->res['result'] = 'success';
            $this->res['state'] = true;
        }

        return $response->withJson($this->res);
    }
}