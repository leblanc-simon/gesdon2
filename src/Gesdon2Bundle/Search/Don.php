<?php

namespace Gesdon2Bundle\Search;

use Gesdon2Bundle\Repository\DonRepository;
use Symfony\Component\Form\Form;

class Don
{
    /**
     * @var DonRepository
     */
    private $repository;

    /**
     * @var Form
     */
    private $form;

    public function __construct(DonRepository $repository)
    {
        $this->repository = $repository;
    }

    public function setForm(Form $form)
    {
        $this->form = $form;
        return $this;
    }

    public function getItems()
    {
        return $this->repository->findBySearch($this->form);
    }
}