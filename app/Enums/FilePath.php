<?php

namespace App\Enums;

enum FilePath: string
{
    case ISSUES = 'employee-relations/issues';
    case INCIDENTS = 'employee-relations/incidents';
    case PRE_EMPLOYMENT = 'uploads/applicant/applications/pre-emp';
    case RESUME = 'uploads/applicant/applications/resume';
    case RESIGNATION = 'uploads/employee/resignation';
}
