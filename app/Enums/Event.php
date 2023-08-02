<?php

namespace App\Enums;

enum Event: string
{
    case AddCategory = 'addCategory';
    case SelectCategory = 'selectCategory';
    case InputCharge = 'inputCharge';
    case Report = 'report';
    case Skip = 'skip';
}
