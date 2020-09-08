<?php


namespace App\ScraperService;


use Goutte\Client;

class DailyReposrt
{

    private $key = [];
    private $value = [];

    public function renderData()
    {
        $c = new Client();
        $data = $c->request("GET", "https://www.worldometers.info/coronavirus/");

        $k = $data->filter("table  > tbody > tr")->each(
            function ($item) {
                $this->key[] = str_replace(":", " ", $item->text());
            }
        );

        $v = $data->filter(".main_table_countries_today  > span")->each(
            function ($item) {
                $this->value[] = $item->text();
            }
        );
        $detaille = [];
        foreach (array_slice($this->key, 8) as $item => $value) {
            $detaille[$item] = explode(" ", $value);
        }
        $nogmeerDetaills = [];
        foreach ($detaille as $item => $value) {
            if (is_string($value[1])) {
                $nogmeerDetaills[$value[1]] = [
                    "totalCases" => $value[2],
                    "newCases" => $value[3],
                    "death" => $value[5] ?? ''
                ];
            }
        }


        return json_encode($nogmeerDetaills);

    }
}
