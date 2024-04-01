<?php

namespace App\Modules\Transaction\Services;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\Modules\Account\Repositories\Contracts\AccountRepositoryInterface;
use App\Modules\Transaction\Repositories\Contracts\TransactionRepositoryInterface;
use App\Modules\Transaction\Services\Contracts\TransferTransactionServiceInterface;

class TransferTransactionService implements TransferTransactionServiceInterface
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
        return $this->transfer($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function transfer($data): JsonResponse
    {
        $user = auth()->user();

        if (!$account = $this->accountRepository->firstAccount((object) ['user_id' => $user->id])) {
            return response()->json(["error" => "Usuário não possui uma conta"]);
        }

        $this->accountRepository->setUserId(null);

        if (!$account_favored = $this->accountRepository->firstAccount((object) [
            'cpf_cnpj' => $data->cpf_cnpj,
            'agency', '=', $data->agency,
            'number_account', '=', $data->number_account
        ])) {
            return response()->json(["error" => "Favorecido informado não possui uma conta"]);
        }

        $transaction = (object) [
            'account_id' => $account->id,
            'start_date' => Carbon::now()->format('Y-m-d H:i:s')
        ];
        $balance = $this->transactionRepository->balance($transaction);

        if ($balance->balance < $data->value) {
            return response()->json(["error" => "Saldo insuficiente, efetue um depósito"]);
        }

        if ($data->cpf_cnpj == $account->cpf_cnpj) {
            return response()->json(["error" => "Você não pode transferir dinheiro para o seu CPF/CNPJ"]);
        }

        if (!$userTransfer = $this->createTransactionService->handler((object) [
            'account_id' => $account->id,
            'user_id' => $user->id,
            'type_transaction_id' => 4,
            'value' => -$data->value,
            'balance' => $balance->balance - $data->value,
            'document' => $data->document,
            'description' => $data->description
        ])) {
            return response()->json(["error" => "Falha ao transferir o dinheiro"]);
        }

        $transactionFavored = (object) [
            'account_id' => $account_favored->id,
            'start_date' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $balance_favored = $this->transactionRepository->balance($transactionFavored);

        if (!$favoredTransfer = $this->createTransactionService->handler((object) [
            'account_id' => $account_favored->id,
            'user_id' => $account_favored->user_id,
            'type_transaction_id' => 4,
            'value' => $data->value,
            'balance' => $balance_favored->balance + $data->value,
            'document' => $data->document,
            'description' => "Credito transferência"
        ])) {
            return response()->json(["error" => "Falha ao transferir o dinheiro"]);
        }

        return response()->json([
            "success" => "Transferência efetuada com sucesso",
            "favored" => $favoredTransfer,
            "account" => $userTransfer
        ]);

    }

    /**
     * @param $key
     * @param $value
     * @return null
     */
    public function __set($key, $transactionRepository)
    {
        $this->transactionRepository->user_id = null;
    }
}
