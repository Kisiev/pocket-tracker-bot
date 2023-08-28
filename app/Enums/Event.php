<?php

namespace App\Enums;

enum Event: string
{
    case AddCategory = 'addCategory';
    case DeleteCategory = 'deleteCategory';
    case SelectTransferCategory = 'selectTransferCategory';
    case SelectCategory = 'selectCategory';
    case InputCharge = 'inputCharge';
    case Report = 'report';
    case Export = 'export';
    case Skip = 'skip';
}
