<?php


namespace App\ScraperService;

use Goutte\Client;
use Illuminate\Support\Facades\File;

class Scraper
{
    private $key = [];
    private $value = [];


    /**
     * @return false|string
     */
    public function totalOverview()
    {
        $c = new Client();
        $data = $c->request("GET", "https://www.worldometers.info/coronavirus/");

        $k = $data->filter("#maincounter-wrap  > h1")->each(
            function ($item) {

                $this->key[] = str_replace(":", " ", $item->text());

            }
        );

        $v = $data->filter(".maincounter-number  > span")->each(
            function ($item) {
                $this->value[] = $item->text();
            }
        );

        $ar = array_combine($this->key, $this->value);
        return json_encode(["overview" => $ar]);
    }


    public function createJsonFile(){
        $file = date('Y-m-d'). '_file.json';
        $destinationPath=public_path()."/ScraperStorage/json/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$file,$this->totalOverview());
        echo "done";
        return;
    }
}
