<?php

declare(strict_types=1);

namespace App\Presentation\Home;

use Nette;
use Nette\Application\UI\Form;
use Nette\Database\Explorer;

final class HomePresenter extends Nette\Application\UI\Presenter
{
    private Explorer $db;

    public function __construct(Explorer $db)
    {
        $this->db = $db;
    }

    public function renderDefault(?int $id = null): void
    {
        $this->template->users = $this->db->table('users')->fetchAll();
        $this->template->editing = false;

        if ($id) {
            $user = $this->db->table('users')->get($id);
            if ($user) {
                $this['userForm']->setDefaults($user->toArray());
                $this->template->editing = true;
            }
        }
    }

    protected function createComponentUserForm(): Form
    {
        $form = new Form;
        $form->addText('first_name', 'First name:')->setRequired();
        $form->addText('last_name', 'Last name:')->setRequired();
        $form->addEmail('email', 'Email:')->setRequired();
        $form->addPassword('password', 'Password:')->setRequired();
        $form->addSelect('role', 'Role:', [
            'admin' => 'Admin',
            'librarian' => 'Librarian',
            'student' => 'Student',
        ]);
        $form->addSubmit('send', 'Save');
        $form->onSuccess[] = [$this, 'userFormSucceeded'];
        return $form;
    }

    public function userFormSucceeded(Form $form, \stdClass $values): void
    {
        $id = $this->getParameter('id');
        $username = strtolower($values->first_name . '.' . $values->last_name);

        $data = [
            'username' => $username,
            'first_name' => $values->first_name,
            'last_name' => $values->last_name,
            'email' => $values->email,
            'password' => password_hash($values->password, PASSWORD_DEFAULT),
            'role' => $values->role,
        ];

        if ($id) {
            $this->db->table('users')->get($id)?->update($data);
            $this->flashMessage('User updated');
        } else {
            $this->db->table('users')->insert($data);
            $this->flashMessage('User added');
        }

        $this->redirect('default');
    }

    public function handleDelete(int $id): void
    {
        $this->db->table('users')->get($id)?->delete();
        $this->flashMessage('User deleted');
        $this->redirect('this');
    }
}