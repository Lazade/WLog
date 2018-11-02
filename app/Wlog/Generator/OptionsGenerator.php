<?php 

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */

namespace App\Wlog\Generator;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\Options;
use App\Wlog\Interfaces\GeneratorInterface;

class OptionsGenerator
{

    private $_error = '';

    public function create(GeneratorInterface $observer, Request $request)
    {
        $options = new Options();
        $options = $this->transform($options, $request);

        if (!$options) {
            $observer->generatorFail('error');
        }

        $observer->generatorSuccess($options);
    }

    public function update(GeneratorInterface $observer, Request $request) 
    {
        if (empty($request->id)) {
            $observer->generatorFail('ID is not allowed empty');
        }
        $options = Options::firstOrCreate(['option_key' => $request->option_key]);
        $options = $this->transform($options, $request);

        if (!$observer) {
            $observer->generatorFail('error');
        }

        $observer->generatorSuccess($options);
    }

    public function transform(Options $options, Request $request) 
    {
        $options->option_key = $request->option_key;
        $options->option_value = $request->option_value;
        $options->type = $request->type;
        $options->data_type = $request->data_type;
        try {
            $options->save();
            return $options;
        } catch (QueryException $exception) {
            if ($exception->errorInfo[1] == 1062) {
                $this->_error = 'Fail: [flag] has existed.';
            }
            return null;
        }
    }

}