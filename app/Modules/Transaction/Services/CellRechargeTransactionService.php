<?php

namespace App\Modules\Transaction\Services;

use Carbon\Carbon;
use App\Libraries\Phone;
use Illuminate\Http\JsonResponse;
use App\Modules\Account\Repositories\Contracts\AccountRepositoryInterface;
use App\Modules\Transaction\Repositories\Contracts\TransactionRepositoryInterface;
use App\Modules\Transaction\Services\Contracts\CellRechargeTransactionServiceInterface;

class CellRechargeTransactionService implements CellRechargeTransactionServiceInterface
{
    private TransactionRepositoryInterface $transactionRepository;
    private AccountRepositoryInterface $accountRepository;
    private CreateTransactionService $createTransactionService;

    /**
     * @param TransactionRepositoryInterface $transactionRepository
     * @param AccountRepositoryInterface $accountRepository
     * @param CreateTransactionService $createTransactionService
     */
    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        AccountRepositoryInterface $accountRepository,
        CreateTransactionService $createTransactionService
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
        $this->createTransactionService = $createTransactionService;

    }

    /**
     * @param $data
     * @return mixed
     */
    public function handler($data): JsonResponse
    {
        return $this->recharge($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function recharge($data): JsonResponse
    {
        $user = auth()->user();
        if (!$account = $this->accountRepository->firstAccount((object) ['user_id' => $user->id])) {
            return response()->json(["error" => "Usuário não possui uma conta"]);
        }

        $transaction = (object) [
            'account_id' => $account->id,
            'start_date' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $balance = $this->transactionRepository->balance($transaction);
        if ($balance->balance < $data->value) {
            return response()->json(["error" => "Saldo insuficiente, por favor efetue um depósito"]);
        }

        $phone = new Phone();
        $phone->phone = $data->phone;

        if ($phone->phone() == false) {
            return response()->json(["error" => "Número do celular inválido"]);
        }

        return $this->createTransactionService->handler((object) [
            'account_id' => $account->id,
            'user_id' => $user->id,
            'type_transaction_id' => 5,
            'value' => -$data->value,
            'balance' => $balance->balance - $data->value,
            'document' => $data->document,
            'number_phone' => $data->phone,
            'description' => $data->description
        ]);
    }
}
