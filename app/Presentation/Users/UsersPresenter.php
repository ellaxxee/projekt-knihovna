<?php

declare(strict_types=1);

namespace App\Presentation\Users;

use Nette;
use Nette\Application\UI\Form;
use Nette\Database\Explorer;

final class UsersPresenter extends Nette\Application\UI\Presenter
{
    private Explorer $database;

    public function __construct(Explorer $database)
    {
        parent::__construct();
        $this->database = $database;
    }

    public function renderDefault(): void
    {
        $this->template->users = $this->database->table('users')->fetchAll();
    }

    public function renderEdit(?int $id = null): void
    {
        if ($id) {
            $user = $this->database->table('users')->get($id);
            if (!$user) {
                $this->error('Uživatel nenalezen.');
            }
            $this['userForm']->setDefaults($user->toArray());
        }
    }

    protected function createComponentUserForm(): Form
    {
        $form = new Form;
        $form->addText('username', 'Uživatelské jméno:')
            ->setRequired('Zadej jméno');
        $form->addEmail('email', 'E-mail:')
            ->setRequired('Zadej e-mail');
        $form->addPassword('password', 'Heslo:')
            ->setRequired('Zadej heslo');
        $form->addSubmit('send', 'Uložit');
        $form->onSuccess[] = [$this, 'userFormSucceeded'];
        return $form;
    }

    public function userFormSucceeded(Form $form, \stdClass $values): void
    {
        $id = $this->getParameter('id');

        if ($id) {
            $this->database->table('users')->get($id)->update([
                'username' => $values->username,
                'email' => $values->email,
                'password' => password_hash($values->password, PASSWORD_DEFAULT),
            ]);
            $this->flashMessage('Uživatel byl upraven.', 'success');
        } else {
            $this->database->table('users')->insert([
                'username' => $values->username,
                'email' => $values->email,
                'password' => password_hash($values->password, PASSWORD_DEFAULT),
            ]);
            $this->flashMessage('Uživatel byl vytvořen.', 'success');
        }

        $this->redirect('default');
    }

    public function handleDelete(int $id): void
    {
        $this->database->table('users')->where('id', $id)->delete();
        $this->flashMessage('Uživatel byl smazán.', 'info');
        $this->redirect('this');
    }
}