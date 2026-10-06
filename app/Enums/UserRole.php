<?php

namespace App\Enums;

enum UserRole: string
{
    case Student = 'student';
    case CampusAdmin = 'campus_admin';
    case SuperAdmin = 'super_admin';
}
