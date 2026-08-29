<?php

namespace App\Http\Requests\Procedure;

use App\Concerns\ProcedureValidationRules;
use App\Models\Procedure;
use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;

class StoreProcedureRequest extends FormRequest
{
    use ProcedureValidationRules;

    public function authorize(): bool
    {
        return $this->route('service') instanceof Service
            && $this->user()->can('create', Procedure::class);
    }

    public function rules(): array
    {
        /** @var Service $service */
        $service = $this->route('service');

        return $this->procedureRules($service);
    }
}
