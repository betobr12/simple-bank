<?php

namespace App\Modules\Transaction\Services;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\Modules\Card\Repositories\Contracts\CardRepositoryInterface;
use App\Modules\Account\Repositories\Contracts\AccountRepositoryInterface;
use App\Modules\Transaction\Repositories\Contracts\TransactionRepositoryInterface;
use App\Modules\Transaction\Services\Contracts\CardPaymentTransactionServiceInterface;
use App\Modules\CardTransaction\Repositories\Contracts\CardTransactionRepositoryInterface;

class CardPaymentTransactionService implements CardPaymentTransactionServiceInterface
{
    private TransactionRepositoryInterface $transactionRepository;
    private AccountRepositoryInterface $accountRepository;
    private CreateTransactionService $createTransactionService;
    private CardTransactionRepositoryInterface $cardTransactionRepository;
    private CardRepositoryInterface $cardRepository;

    /**
     * @param TransactionRepositoryInterface $transactionRepository
     * @param AccountRepositoryInterface $accountRepository
     * @param CreateTransactionService $createTransactionService
     * @param CardTransactionRepositoryInterface $cardTransactionRepository
     * @param CardRepositoryInterface $cardRepository
     */
    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        AccountRepositoryInterface $accountRepository,
        CreateTransactionService $createTransactionService,
        CardTransactionRepositoryInterface $cardTransactionRepository,
        CardRepositoryInterface $cardRepository
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;
        $this->createTransactionService = $createTransactionService;
        $this->cardTransactionRepository = $cardTransactionRepository;
        $this->cardRepository = $cardRepository;
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

        if (!$card = $this->cardRepository->firstCard((object) [
            'user_id', '=', $user->id,
            'account_id', '=', $account->id
        ])) {
            return response()->json(["error" => "Usuário não possui um cartão"]);
        }

        if (!$this->createTransactionService->handler((object) [
            'account_id' => $account->id,
            'user_id' => $user->id,
            'type_transaction_id' => 4,
            'value' => -$data->value,
            'balance' => $balance->balance - $data->value,
            'document' => $data->document,
            'number_card' => $card->number_card,
            'description' => $data->description
        ])) {
            return response()->json(["error" => "Erro ao realizar a transação"]);
        }

        $cardTransaction = (object) [];
        $cardTransaction->account_id = $account->id;
        $cardTransaction->start_date = \Carbon\Carbon::now();
        $balanceCard = (object) $this->cardTransactionRepository->balance($cardTransaction);

        if ($balanceCard->balance >= 0) {
            return response()->json(["success" => "Não há pendências"]);
        }

        if ($cardTrans = $this->cardTransactionRepository->create([
            'account_id' => $account->id,
            'user_id' => $user->id,
            'card_id' => $card->id,
            'value' => $data->value,
            'balance' => $balanceCard->balance + $data->value,
            'store' => "Pagamento do Cartão",
            'description' => $data->description,
            'date' => \Carbon\Carbon::now(),
            'created_at' => \Carbon\Carbon::now()
        ])) {
            return response()->json(["success" => "Cartão de credito pago com sucesso", "cardTransaction" => $cardTrans]);
        }
        return response()->json(["error" => "Erro ao realizar o acesso a transação do cartão"]);

    }
}
