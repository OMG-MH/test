<?php

namespace App\Http\Controllers\User;

use App\Lib\FormProcessor;
use App\Models\Withdrawal;
use App\Models\Transaction;
use App\Models\WithdrawMethod;
use App\Constants\ManageStatus;
use App\Models\AdminNotification;
use App\Http\Controllers\Controller;

class WithdrawController extends Controller
{
    function withdraw() {
        $pageTitle = 'Withdraw Money';
        $methods   = WithdrawMethod::active()->get();
        $user      = auth()->user();

        return view($this->activeTheme . 'user.withdraw.index', compact('pageTitle', 'methods', 'user'));
    }

    function store() {
        $this->validate(request(), [
            'method_id' => 'required|int|gt:0',
            'amount'    => 'required|numeric|gt:0',
        ]);

        $user   = auth()->user();
        $amount = request('amount');
        $method = WithdrawMethod::where('id', request('method_id'))->active()->firstOrFail();

        if ($amount < $method->min_amount) {
            $toast[] = ['error', 'Requested amount cannot be less than the minimum amount'];

            return back()->with('toasts', $toast);
        }

        if ($amount > $method->max_amount) {
            $toast[] = ['error', 'Requested amount cannot be greater than the maximum amount'];

            return back()->with('toasts', $toast);
        }

        if ($amount > $user->balance) {
            $toast[] = ['error', 'You don\'t have enough amount to make this withdrawal'];

            return back()->with('toasts', $toast);
        }

        $charge      = $method->fixed_charge + ($amount * $method->percent_charge / 100);
        $afterCharge = $amount - $charge;
        $finalAmount = $afterCharge * $method->rate;

        $withdraw               = new Withdrawal();
        $withdraw->method_id    = $method->id;
        $withdraw->user_id      = $user->id;
        $withdraw->amount       = $amount;
        $withdraw->currency     = $method->currency;
        $withdraw->rate         = $method->rate;
        $withdraw->charge       = $charge;
        $withdraw->final_amount = $finalAmount;
        $withdraw->after_charge = $afterCharge;
        $withdraw->trx          = getTrx();
        $withdraw->save();

        session()->put('wtrx', $withdraw->trx);

        return to_route('user.withdraw.preview');
    }

    function preview() {
        $pageTitle = 'Withdraw Preview';
        $withdraw  = Withdrawal::with(['method', 'user'])
            ->where('trx', session()->get('wtrx'))
            ->initiate()
            ->firstOrFail();
        $user      = auth()->user();

        return view($this->activeTheme . 'user.withdraw.preview', compact('pageTitle', 'withdraw', 'user'));
    }

    function submit() {
        $withdraw = Withdrawal::with(['method', 'user'])
            ->where('trx', session()->get('wtrx'))
            ->initiate()
            ->firstOrFail();

        $method = $withdraw->method;

        if ($method->status == ManageStatus::INACTIVE) abort(404);

        $formData       = $method->form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);

        $this->validate(request(), $validationRule);

        $user = auth()->user();

        if ($user->ts) {
            $response = verifyG2fa($user, request('authenticator_code'));

            if (!$response) {
                $toast[] = ['error', 'Wrong verification code'];

                return back()->with('toasts', $toast);
            }
        }

        if ($withdraw->amount > $user->balance) {
            $toast[] = ['error', 'You don\'t have enough amount to make this withdrawal'];

            return back()->with('toasts', $toast);
        }

        $withdraw->withdraw_information = $formProcessor->processFormData(request(), $formData);
        $withdraw->status               = ManageStatus::PAYMENT_PENDING;
        $withdraw->save();

        $user->balance -= $withdraw->amount;
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $withdraw->user_id;
        $transaction->amount       = $withdraw->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = $withdraw->charge;
        $transaction->trx_type     = '-';
        $transaction->details      = showAmount($withdraw->final_amount) . ' ' . $withdraw->currency . ' Withdraw via ' . $withdraw->method->name;
        $transaction->trx          = $withdraw->trx;
        $transaction->remark       = 'withdraw';
        $transaction->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New withdrawal request from ' . $user->username;
        $adminNotification->click_url = urlPath('admin.withdraw.pending');
        $adminNotification->save();

        notify($user, 'WITHDRAW_REQUEST', [
            'method_name'     => $withdraw->method->name,
            'method_amount'   => showAmount($withdraw->final_amount),
            'method_currency' => $withdraw->currency,
            'amount'          => showAmount($withdraw->amount),
            'charge'          => showAmount($withdraw->charge),
            'rate'            => showAmount($withdraw->rate),
            'trx'             => $withdraw->trx,
            'post_balance'    => showAmount($user->balance),
        ]);

        $toast[] = ['success', 'Withdrawal request has submitted'];

        return to_route('user.withdraw.history')->with('toasts', $toast);
    }

    function withdrawHistory() {
        $pageTitle   = 'Withdraw History';
        $user        = auth()->user();
        $withdrawals = $user->withdrawals()
            ->with('method')
            ->index()
            ->searchable(['trx'])
            ->latest()
            ->paginate(getPaginate());

        return view($this->activeTheme . 'user.withdraw.history', compact('pageTitle', 'withdrawals', 'user'));
    }
}
