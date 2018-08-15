<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use DB;

class DashboardController extends Controller {
    public function index() {
        $user = Auth::user();

        $spendingsToday = $user
            ->spendings()
            ->whereRaw('DATE(happened_on) = ?', [date('Y-m-d')])
            ->sum('amount');

        $spendingsMonth = $user
            ->spendings()
            ->whereRaw('MONTH(happened_on) = ?', [date('m')])
            ->sum('amount');

        $mostExpensiveTag = DB::select('
            SELECT
                tags.name AS name,
                SUM(spendings.amount) AS amount
            FROM
                tags
            LEFT OUTER JOIN
                spendings ON tags.id = spendings.tag_id
            WHERE
                tags.user_id = ?
            GROUP BY
                tags.id
            ORDER BY
                SUM(spendings.amount) DESC
            LIMIT 1;
        ', [$user->id])[0];

        $recentEarnings = $user
            ->earnings()
            ->orderBy('happened_on', 'DESC')
            ->limit(3)
            ->get();

        $recentSpendings = $user
            ->spendings()
            ->orderBy('happened_on', 'DESC')
            ->limit(3)
            ->get();

        return view('dashboard', [
            'currency' => $user->currency,

            'spendingsToday' => $spendingsToday,
            'spendingsMonth' => $spendingsMonth,
            'mostExpensiveTag' => $mostExpensiveTag,

            'recentEarnings' => $recentEarnings,
            'recentSpendings' => $recentSpendings
        ]);
    }
}
