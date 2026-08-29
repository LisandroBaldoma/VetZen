<?php

namespace App\Http\Requests\Procedure;

use App\Concerns\ProcedureValidationRules;
use App\Models\Procedure;
use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProcedureRequest extends FormRequest
{
    use ProcedureValidationRules;

    public function authorize(): bool
    {
        $procedure = $this->route('procedure');

        return $this->route('service') instanceof Service
            && $procedure instanceof Procedure
            && $this->user()->can('update', $procedure);
    }

    public function rules(): array
    {
        /** @var Service $service */
        $service = $this->route('service');
        /** @var Procedure $procedure */
        $procedure = $this->route('procedure');

        return $this->procedureRules($service, $procedure);
    }
}
