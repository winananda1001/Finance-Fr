<?php

namespace App\Http\Controllers;

use App\Repositories\RecurringRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private $recurringRepository;

    public function __construct(TransactionRepository $transactionRepository, RecurringRepository $recurringRepository)
    {
        $this->repository = $transactionRepository;
        $this->recurringRepository = $recurringRepository;
    }

    public function index(Request $request)
    {
        $filterBy = [];

        if ($request->get('filterBy')) {
            $filterBy = explode('-', $request->get('filterBy'));
        }

        return view('transactions.index', [
            'yearMonths' => $this->repository->getTransactionsByYearMonth($filterBy),
            'tags' => session('space')->tags
        ]);
    }

    public function create()
    {
        $tags = [];

        foreach (session('space')->tags as $tag) {
            $tags[] = [
                'key' => $tag->id,
                'label' => '<div class="row"><div class="row__column row__column--compact row__column--middle mr-1"><div style="width: 15px; height: 15px; border-radius: 2px; background: #' . $tag->color . ';"></div></div><div class="row__column row__column--middle">' . $tag->name . '</div></div>' // phpcs:ignore
            ];
        }

        return view('transactions.create', [
            'tags' => $tags,
            'recurringsIntervals' => $this->recurringRepository->getSupportedIntervals()
        ]);
    }
}
