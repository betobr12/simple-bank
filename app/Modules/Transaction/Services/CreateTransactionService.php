<?php

namespace App\Modules\Transaction\Services;

use App\Transaction;
use Illuminate\Http\JsonResponse;
use App\Modules\Account\Repositories\Contracts\AccountRepositoryInterface;
use App\Modules\Transaction\Services\Contracts\CreateTransactionServiceInterface;
use App\Modules\Transaction\Repositories\Contracts\TransactionRepositoryInterface;

class CreateTransactionService implements CreateTransactionServiceInterface
{
    private TransactionRepositoryInterface $transactionRepository;
    private AccountRepositoryInterface $accountRepository;
    /**
     * @param TransactionRepositoryInterface $userRepository
     */
    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        AccountRepositoryInterface $accountRepository
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function handler($data): JsonResponse
    {
        return $this->createTransaction($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function createTransaction($data): JsonResponse
    {
        if ($this->transactionRepository->create([
            'account_id' => $data->account_id,
            'user_id' => $data->user_id,
            'type_transaction_id' => $data->type_transaction_id ?? null,
            'value' => $data->value,
            'balance' => $data->balance,
            'document' => $data->document ?? null,
            'number_card' => $data->number_card ?? null,
            'number_phone' => $data->number_phone ?? null,
            'description' => $data->description ?? null,
            'date' => \Carbon\Carbon::now(),
            'created_at' => \Carbon\Carbon::now()
        ])) {
            return response()->json([
                "success" => "Transação efetuada com sucesso",
                "description" => $data->description,
                "value" => $data->value,
                "balance" => $data->balance
            ]);
        }
        return response()->json(["error" => "Não foi possível realizar o depósito"]);
    }
}
