<?php

namespace App\Modules\Account\Services;

use App\Libraries\Document;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Libraries\GenerateAccount;
use App\Modules\Person\Repositories\Contracts\PersonRepositoryInterface;
use App\Modules\Account\Repositories\Contracts\AccountRepositoryInterface;
use App\Modules\Company\Repositories\Contracts\CompanyRepositoryInterface;
use App\Modules\Account\Services\Contracts\AccountRegisterServiceInterface;

class AccountRegisterService implements AccountRegisterServiceInterface
{
    private Document $document;
    private AccountRepositoryInterface $accountRepository;
    private GenerateAccount $generateAccount;
    private CompanyRepositoryInterface $companyRepository;
    private PersonRepositoryInterface $personRepository;

    /**
     * @param Document $document
     * @param AccountRepositoryInterface $accountRepository
     */
    public function __construct(
        Document $document,
        AccountRepositoryInterface $accountRepository,
        GenerateAccount $generateAccount,
        CompanyRepositoryInterface $companyRepository,
        PersonRepositoryInterface $personRepository
    ) {
        $this->document = $document;
        $this->accountRepository = $accountRepository;
        $this->generateAccount = $generateAccount;
        $this->companyRepository = $companyRepository;
        $this->personRepository = $personRepository;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function handler($data): JsonResponse
    {
        return $this->accountRegister($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function accountRegister(Request $data): JsonResponse
    {
        $user = auth()->user();

        $cpfCnpj = preg_replace('/[^0-9]/', '', $data->cpf_cnpj);
        $this->document->cpf_cnpj = $cpfCnpj;

        if (!($autenticateDocument = $this->autenticateDocument($cpfCnpj, $this->document))->success) {
            return response()->json($autenticateDocument);
        }

        $type = strlen($cpfCnpj) == 11 ? 2 : 1;

        if ($this->accountRepository->get((object) [
            'cpf_cnpj' => $cpfCnpj
        ])) {
            return response()->json(['error' => 'CPF/CNPJ Cadastrado para outra conta']);
        }

        if ($this->accountRepository->get((object) [
            'user_id' => $user->id
        ])) {
            return response()->json(['error' => 'Conta já cadastrada para este usuário']);
        }

        $this->generateAccount->cpf_cnpj = $cpfCnpj;
        $this->generateAccount->id = $user->id;
        $this->generateAccount->date = $user->created_at;
        $accountData = $this->generateAccount->accountNumber();

        if (!$account = $this->accountRepository->create([
            'user_id' => $user->id,
            'cpf_cnpj' => $data->cpf_cnpj,
            'agency' => 1,
            'type_id' => $type,
            'number_account' => $accountData->account_number,
            'digit' => $accountData->digit,
            'created_at' => \Carbon\Carbon::now()
        ])) {
            return response()->json(["error" => "Erro ao cadastrar a conta", "data" => []]);
        }
        return $this->createType($account, $data);
    }

    /**
     * @param $cpfCnpj
     * @param $document
     */
    private function autenticateDocument($cpfCnpj, $document): object
    {
        if (!$document->validdateCpfOrCnpj($cpfCnpj)) {
            return (object) ['success' => false, 'error' => 'CPF/CNPJ inválido'];
        }
        return (object) ['success' => true, 'message' => 'Ok'];
    }

    /**
     * @param $type
     */
    private function createType($account, $data): JsonResponse
    {
        if ($account->type_id == 1) {
            return $this->companyCreate($account, $data, $account);
        }

        if ($account->type_id == 2) {
            return $this->peopleCreate($account, $data, $account);
        }
        return response()->json(["error" => "Erro ao cadastrar a conta", "data" => []]);
    }

    /**
     * @param $company
     * @param $data
     */
    private function companyCreate($company, $data, $account): JsonResponse
    {
        if ($this->personRepository->create([
            'account_id' => $account->id,
            'user_id' => $account->user_id,
            'name' => $data->name,
            'created_at' => \Carbon\Carbon::now()
        ])) {
            return response()->json(["success" => true, "message" => "Conta tipo Person criada com sucesso", "data" => $account->get()]);
        }

    }

    /**
     * @param $people
     * @param $data,
     */
    private function peopleCreate($people, $data, $account): JsonResponse
    {
        if ($this->companyRepository->create([
            'account_id' => $account->id,
            'user_id' => $account->user_id,
            'social_reason' => $data->social_reason,
            'fantasy_name' => $data->fantasy_name,
            'created_at' => \Carbon\Carbon::now()
        ])) {
            return response()->json(["success" => true, "message" => "Conta tipo Company criada com sucesso", "data" => $account->get()]);
        }
    }
}
