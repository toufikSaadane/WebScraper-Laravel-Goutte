<?php


namespace App\Factory;


use App\ScraperService\DailyReposrt;
use App\ScraperService\Overview;
use Illuminate\Support\Facades\File;

class FileFactory
{
    public function createFile($command)
    {
        $file = date('Y-m-d') . '_refactoring_file.json';
        $destinationPath = public_path() . "/ScraperStorage/json/";
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        if (in_array($command, ["overview", "dailyReport"])) {
            if ($command == 'overview') {
                File::put($destinationPath . $file, (new Overview())->data());
            } elseif ($command == 'dailyReport') {
                File::put($destinationPath . $file, (new DailyReposrt())->renderData());
            }
        } else {
            echo "choose one the following reports type:\n --overview\n --dailyReport\n";
        }
        echo "done\n";
        return;
    }

}
