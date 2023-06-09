<?php

declare(strict_types=1);

namespace AppCore\Domain\Authorization;

enum Permission
{
    case All;
    case Account;

    case MemberList;
    case MemberView;
    case MemberCreate;
    case MemberUpdate;
    case MemberDelete;

    case RoleList;
    case RoleView;
    case RoleCreate;
    case RoleUpdate;
    case RoleDelete;

    case BillingList;
    case BillingView;

    // Ex.
//    case BlogList;
//    case BlogGet;
//    case BlogCreate;
//    case BlogUpdate;
//    case BlogDelete;

//    case NewsList;
//    case NewsGet;
//    case NewsCreate;
//    case NewsUpdate;
//    case NewsDelete;
}
