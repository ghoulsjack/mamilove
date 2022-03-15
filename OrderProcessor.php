<?php

use BillerInterface;
use Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderProcessor
{

    public function process(Order $order, BillerInterface $biller)
    {
        $recent = $this->getRecentOrderCount($order);

        if ($recent > 0)
        {
            throw new Exception('Duplicate order likely.');
        }

        DB::beginTransaction();

        $biller->bill($order->account->id, $order->amount);

        $result = DB::table('orders')->insert(array(
            'account'    => $order->account->id,
            'amount'     => $order->amount,
            'created_at' => Carbon::now()
        ));

        if (empty($result))
        {
            DB::rollBack();
        }
        else
        {
            DB::commit();
        }
        return $result;
    }

    protected function getRecentOrderCount(Order $order)
    {
        $timestamp = Carbon::now()->subMinutes(5);

        return DB::table('orders')
            ->where('account', $order->account->id)
            ->where('created_at', '>=', $timestamp)
            ->count();
    }
}