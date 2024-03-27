<?php

namespace App\Modules\Account\Services;

use App\Card;
use App\Account;
use App\Transaction;
use PHPUnit\Util\Json;
use App\CardTransaction;
use App\Libraries\Document;
use Illuminate\Http\JsonResponse;
use App\Modules\Account\Services\Contracts\AccountListServiceInterface;
use App\Modules\Account\Repositories\Contracts\AccountRepositoryInterface;

class AccountListService implements AccountListServiceInterface
{

    private Document $document;
    private AccountRepositoryInterface $accountRepository;

    /**
     * @param Document $document
     * @param AccountRepositoryInterface $userRepository
     */
    public function __construct(
        Document $document,
        AccountRepositoryInterface $accountRepository

    ) {
        $this->document = $document;
        $this->accountRepository = $accountRepository;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function handler($id): JsonResponse
    {
        return $this->list($id);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function list(int $id): JsonResponse
    {
        $user = auth()->user();

        if ($this->accountRepository->get((object) [
            'id' => $id,
            'user_id' => $user->id
        ])) {
            return response()->json(['error' => 'ID informado não pertence a sua conta']);
        }

        if ($this->accountRepository->get((object) [
            'user_id' => $user->id
        ])) {
            return response()->json(['success' => false, 'error' => 'ID informado não pertence a sua conta']);
        }

        $account = new Account();
        $account->account_id = $id;

        $transactions = new Transaction();
        $transactions->account_id = $id;

        $card_transactions = new CardTransaction();
        $card_transactions->account_id = $id;

        $card = new Card();
        $card->account_id = $id;

        return response()->json([
            "data_account" => $this->accountRepository->get((object) ['id' => $id]),
            "card" => $card->getCard(),
            "transactions" => $transactions->getTransactions(),
            "card_transactions" => $card_transactions->getCardTransactions()]
        );

    }
}
