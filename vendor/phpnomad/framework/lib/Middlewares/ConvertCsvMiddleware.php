<?php

namespace PHPNomad\Framework\Middlewares;

use PHPNomad\Rest\Interfaces\Middleware;
use PHPNomad\Rest\Interfaces\Request;
use PHPNomad\Utils\Helpers\Arr;

class ConvertCsvMiddleware implements Middleware
{
    protected string $fieldToReplace;
    protected array $validFields;
    protected string $delimiter;

    public function __construct(array $validFields, string $fieldToReplace, string $delimiter = ',')
    {
        $this->validFields = $validFields;
        $this->fieldToReplace = $fieldToReplace;
        $this->delimiter = $delimiter;
    }

    /**
     * Process the request by filtering and replacing a CSV-formatted field with valid fields.
     *
     * @param Request $request The request object to process
     * @return void
     */
    public function process(Request $request): void
    {
        $fieldCsv = $request->getParam($this->fieldToReplace);
        $fields = Arr::filter(
            explode($this->delimiter, $fieldCsv),
            fn(string $field) => in_array($field, $this->validFields)
        );

        $request->setParam($this->fieldToReplace, $fields);
    }
}