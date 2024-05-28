<?php

namespace App\Http\Services;

use App\Enums\EOrderReverse;
use App\Exceptions\OptionNotFoundException;
use App\Models\Option;
use App\Models\Scopes\OptionScope;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class OptionService {

    /**
     * @param array $data
     * @return Collection|LengthAwarePaginator
     * @throws BindingResolutionException
     */
    public function index(array $data): Collection|LengthAwarePaginator
    {
        $order = $data['order'] ?? 'title';
        $reverse = $data['reverse'] ?? EOrderReverse::ASC->value;

        $filter = app()->make(OptionScope::class, [
            'queryParams' => array_filter($data)
        ]);

        $options = Option::query()->filter($filter)
            ->orderBy($order, $reverse);

        return is_null($data['limit'] ?? null)
            ? $options->get()
            : $options->paginate($data['limit']);
    }

    /**
     * @param int $id
     * @return Option
     * @throws OptionNotFoundException
     */
    public function show(int $id): Option
    {
        $option = Option::find($id);

        if (!$option) {
            throw new OptionNotFoundException();
        }

        return $option;
    }

    /**
     * @param array $data
     * @return Option
     */
    public function store(array $data): Option
    {
        return Option::create($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Option
     * @throws OptionNotFoundException
     */
    public function update(int $id, array $data): Option
    {
        $option = Option::find($id);

        if (!$option) {
            throw new OptionNotFoundException();
        }

        $option->update($data);

        return $option;
    }

    /**
     * @param int $id
     * @return void
     * @throws OptionNotFoundException
     */
    public function destroy(int $id): void
    {
        $option = Option::find($id);

        if (!$option) {
            throw new OptionNotFoundException();
        }

        $option->delete();
    }

}
