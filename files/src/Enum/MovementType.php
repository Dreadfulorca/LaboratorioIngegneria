<?php
namespace App\Enum;

enum MovementType: string
{
    case INCOME = 'income';   // entrata
    case EXPENSE = 'expense'; // uscita
}
