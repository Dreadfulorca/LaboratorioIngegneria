<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS)]
class CategoryForExpense extends Constraint
{
    public string $messageMissing = 'Per le uscite la categoria è obbligatoria.';
    public string $messageForbidden = 'Per le entrate non è possibile specificare una categoria.';
}
