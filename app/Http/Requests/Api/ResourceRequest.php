<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ResourceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = config("models.{$this->resourceKey()}.validation", []);

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return $this->rulesForUpdate($rules);
        }

        return $rules;
    }

    private function resourceKey(): string
    {
        $candidates = array_filter([
            $this->route('resource'),
            Str::before((string) $this->route()?->getName(), '.'),
            $this->segment(2),
        ]);

        foreach ($candidates as $candidate) {
            foreach ($this->resourceKeyCandidates((string) $candidate) as $key) {
                if (config()->has("models.{$key}")) {
                    return $key;
                }
            }
        }

        return '';
    }

    /**
     * @return array<int, string>
     */
    private function resourceKeyCandidates(string $resource): array
    {
        $resource = Str::of($resource)->lower()->value();

        return array_values(array_unique([
            $resource,
            str_replace('-', '_', $resource),
            str_replace(['-', '_'], '', $resource),
            Str::singular($resource),
            str_replace('-', '_', Str::singular($resource)),
            str_replace(['-', '_'], '', Str::singular($resource)),
        ]));
    }

    /**
     * @param  array<string, ValidationRule|array<mixed>|string>  $rules
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    private function rulesForUpdate(array $rules): array
    {
        return collect($rules)
            ->map(fn (array|string|ValidationRule $fieldRules, string $field): array|string|ValidationRule => $this->prepareUpdateRules($fieldRules, $field))
            ->all();
    }

    private function prepareUpdateRules(array|string|ValidationRule $rules, string $field): array|string|ValidationRule
    {
        if ($rules instanceof ValidationRule) {
            return $rules;
        }

        $rules = is_string($rules) ? explode('|', $rules) : $rules;

        if (! in_array('sometimes', $rules, true)) {
            array_unshift($rules, 'sometimes');
        }

        return array_map(
            fn (mixed $rule): mixed => $this->prepareUniqueRuleForUpdate($rule, $field),
            $rules
        );
    }

    private function prepareUniqueRuleForUpdate(mixed $rule, string $field): mixed
    {
        if (! is_string($rule) || ! str_starts_with($rule, 'unique:')) {
            return $rule;
        }

        $recordId = $this->routeRecordId();

        if ($recordId === null) {
            return $rule;
        }

        [$table, $column] = array_pad(explode(',', Str::after($rule, 'unique:')), 2, null);

        return Rule::unique($table, $column ?: $field)->ignore($recordId);
    }

    private function routeRecordId(): int|string|null
    {
        foreach (array_reverse($this->route()?->parameters() ?? []) as $parameter) {
            if ($parameter instanceof Model) {
                return $parameter->getKey();
            }

            if (is_scalar($parameter) && ctype_digit((string) $parameter)) {
                return (int) $parameter;
            }
        }

        return null;
    }
}
