<?php

declare(strict_types=1);

namespace App\Presentation\Home;

use Nette;
use Nette\Database\Explorer;

final class HomePresenter extends Nette\Application\UI\Presenter
{
    private Explorer $database;

    public function __construct(Explorer $database)
    {
        parent::__construct();
        $this->database = $database;
    }

    public function renderDefault(): void
    {
        $knihy = $this->database->fetchAll('SELECT * FROM knihy');

       
        $this->template->knihy = $knihy;
        $this->template->promena = "Ahoj, tohle je moje úvodní stránka s databází 📚";
    }
}
