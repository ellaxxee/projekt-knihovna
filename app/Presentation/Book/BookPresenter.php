<?php

declare(strict_types=1);

namespace App\Presentation\Book;

use Nette;
use Nette\Application\UI\Form;
use Nette\Database\Explorer;

final class BookPresenter extends Nette\Application\UI\Presenter
{
    private Explorer $db;

    public function __construct(Explorer $db)
    {
        $this->db = $db;
    }

    public function renderDefault(?int $id = null): void
    {
        $this->template->books = $this->db->table('books')->order('id DESC')->fetchAll();
        $this->template->editing = false;

        if ($id) {
            $book = $this->db->table('books')->get($id);
            if ($book) {
                $this['bookForm']->setDefaults($book->toArray());
                $this->template->editing = true;
            }
        }
    }

    protected function createComponentBookForm(): Form
    {
        $form = new Form;

        $form->addText('title', 'Název knihy:')
            ->setRequired('Zadej název knihy.');

        $form->addText('author', 'Autor:')
            ->setRequired('Zadej jméno autora.');

        $form->addText('isbn', 'ISBN:')
            ->setRequired('Zadej ISBN.');

        $form->addInteger('publication_year', 'Rok vydání:')
            ->setHtmlAttribute('min', 0)
            ->setHtmlAttribute('max', date('Y'))
            ->setRequired('Zadej rok vydání.');

        $form->addText('genre', 'Žánr:')
            ->setRequired('Zadej žánr.');

        $form->addInteger('total_copies', 'Celkem kopií:')
            ->setRequired('Zadej počet všech kopií.')
            ->addRule($form::Min, 'Počet musí být alespoň 1.', 1);

        $form->addInteger('available_copies', 'Dostupné kopie:')
            ->setRequired('Zadej počet aktuálně dostupných kopií.')
            ->addRule($form::Min, 'Počet musí být alespoň 0.', 0);

        $form->addSubmit('send', 'Uložit');
        $form->onSuccess[] = [$this, 'bookFormSucceeded'];

        return $form;
    }

    public function bookFormSucceeded(Form $form, \stdClass $values): void
    {
        $id = $this->getParameter('id');

        $data = [
            'title' => $values->title,
            'author' => $values->author,
            'isbn' => $values->isbn,
            'publication_year' => $values->publication_year,
            'genre' => $values->genre,
            'total_copies' => $values->total_copies,
            'available_copies' => $values->available_copies,
            'added_at' => new \DateTime(),
        ];

        if ($id) {
            $this->db->table('books')->get($id)?->update($data);
            $this->flashMessage('Kniha byla upravena.');
        } else {
            $this->db->table('books')->insert($data);
            $this->flashMessage('Kniha byla přidána.');
        }

        $this->redirect('default');
    }

    public function handleDelete(int $id): void
    {
        $this->db->table('books')->get($id)?->delete();
        $this->flashMessage('Kniha byla smazána.');
        $this->redirect('this');
    }
}