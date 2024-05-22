<?php

namespace Botble\Ecommerce\Tables;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

class CustomerReviewTable extends ReviewTable
{
    protected int|string $customerId;

    public function setup(): void
    {
        parent::setup();

        $this->view = $this->simpleTableView();
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->getModel()
            ->query()
            ->where('customer_id', $this->getCustomerId())
            ->select([
                'id',
                'star',
                'comment',
                'product_id',
                'customer_id',
                'customer_name',
                'customer_email',
                'status',
                'created_at',
                'images',
            ])
            ->with(['user', 'product']);

        return $this->applyScopes($query);
    }

    public function getCustomerId(): int|string
    {
        return $this->customerId;
    }

    public function customerId(int|string $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function renderTable($data = [], $mergeData = []): View|Factory|Response
    {
        return $this->render($this->view, $data, $mergeData);
    }
}
