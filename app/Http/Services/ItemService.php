<?php

namespace App\Http\Services;

use App\Enums\EOrderReverse;
use App\Exceptions\ItemNotFoundException;
use App\Models\Item;
use App\Models\Scopes\ItemScope;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class ItemService {

    /**
     * @param array $data
     * @return Collection|LengthAwarePaginator
     * @throws BindingResolutionException
     */
    public function index(array $data): Collection|LengthAwarePaginator
    {
        $order = $data['order'] ?? 'title';
        $reverse = $data['reverse'] ?? EOrderReverse::ASC->value;

        $filter = app()->make(ItemScope::class, [
            'queryParams' => array_filter($data)
        ]);

        $items = Item::query()->filter($filter)
            ->orderBy($order, $reverse);

        return is_null($data['limit'] ?? null)
            ? $items->get()
            : $items->paginate($data['limit']);
    }

    /**
     * @param int $id
     * @return Item
     * @throws ItemNotFoundException
     */
    public function show(int $id): Item
    {
        $item = Item::find($id);

        if (!$item) {
            throw new ItemNotFoundException();
        }

        return $item;
    }

    /**
     * @param array $data
     * @return Item
     */
    public function store(array $data): Item
    {
        $item = Item::create($data);

        $item->images()->attach(collect($data)->get('images'));

        return $item;
    }

    /**
     * @param int $id
     * @param array $data
     * @return Item
     * @throws ItemNotFoundException
     */
    public function update(int $id, array $data): Item
    {
        $item = Item::find($id);

        if (!$item) {
            throw new ItemNotFoundException();
        }

        $item->update($data);

        $item->images()->sync(collect($data)->get('images'));

        return $item;
    }

    /**
     * @param int $id
     * @return void
     * @throws ItemNotFoundException
     */
    public function destroy(int $id): void
    {
        $item = Item::find($id);

        if (!$item) {
            throw new ItemNotFoundException();
        }

        $item->delete();
    }

}
