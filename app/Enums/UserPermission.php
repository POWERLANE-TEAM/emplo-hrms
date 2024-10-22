<?php

namespace App\Enums;

enum UserPermission: string
{
    /*
     * Set enum cases for permissions here and ensure to follow the laravel
     * resource authorization convention: create, view, update, and delete
     *
     * Format: VERB_RESOURCE_NAME = verb resource name
    */

    // Create cases goes here
    case CREATE_JOB_LISTING = 'create job listing';
    case CREATE_ANNOUNCEMENT = 'create announcement';
    case CREATE_EMPLOYEE_ACCOUNT = 'create employee account';
    case CREATE_BULK_EMPLOYEE_ACCOUNT = 'create bulk employee accounts';

    // View cases goes here
    case VIEW_APPLICANT_INFORMATION = 'view applicant information';
    case VIEW_EMPLOYEE_INFORMATION = 'view employee information';
    case VIEW_EMPLOYEE_DASHBOARD = 'view employee dashboard';
    case VIEW_ATTENDANCE = 'view employee attendance';
    case VIEW_PAYSLIPS = 'view employee payslips';
    case VIEW_PERFORMANCE = 'view employee performance';
    case VIEW_LEAVES = 'view employee leaves';
    case VIEW_OVERTIME = 'view employee overtime';
    case VIEW_DOCUMENTS = 'view employee documents';
    case VIEW_ISSUES = 'view employee issues';
    case VIEW_HR_MANAGER_DASHBOARD = 'view hr manager dashboard';
    case VIEW_ALL_APPLICANTS = 'view all applicants';
    case VIEW_ALL_EMPLOYEES = 'view all employees';
    case VIEW_ALL_ATTENDANCE = 'view all attendance records';
    case VIEW_ALL_LEAVES = 'view all leave records';
    case VIEW_ALL_OVERTIME = 'view all overtime records';
    case VIEW_ALL_PAYSLIPS = 'view all payslips';
    case VIEW_ALL_PERFORMANCE = 'view all performance records';
    case VIEW_ALL_RELATIONS = 'view all relations';
    case VIEW_MATRIX_PROJECTOR = 'view matrix projector';
    case VIEW_TALENT_EVALUATOR = 'view talent evaluator';
    case VIEW_PLAN_GENERATOR = 'view plan generator';
    case VIEW_ADMIN_DASHBOARD = 'view admin dashboard';
    case VIEW_ACCOUNT_MANAGER = 'view account manager';
    case VIEW_EMPLOYEE_MANAGER = 'view employee manager';
    case VIEW_CALENDAR_MANAGER = 'view calendar manager';
    case VIEW_JOB_LISTING_MANAGER = 'view job listing manager';
    case VIEW_POLICY_MANAGER = 'view policies manager';
    case VIEW_ANNOUNCEMENT_MANAGER = 'view announcement manager';
    case VIEW_PERFORMANCE_CONFIG = 'view performance configurator';
    case VIEW_FORM_CONFIG = 'view form configurator';


    // Update cases goes here
    case UDPATE_JOB_LISTING = 'update job listing';
    case UPDATE_ANNOUNCEMENT = 'update announcement';

    // Delete cases goes here
    case DELETE_JOB_LISTING = 'delete job listing';
    case DELETE_ANNOUNCEMENT = 'delete announcement';

    /**
     * Return user-friendly permission labels.
     * 
     * @return string
     */
    public function label(): string
    {
        return match ($this) {

            // Create labels goes here
            self::CREATE_JOB_LISTING => 'Publish job listing/s',
            self::CREATE_ANNOUNCEMENT => 'Publish announcement/s',
            self::CREATE_EMPLOYEE_ACCOUNT => 'Create an employee account ',
            self::CREATE_BULK_EMPLOYEE_ACCOUNT => 'Create employee accounts',

            // View labels goes here
            self::VIEW_APPLICANT_INFORMATION => 'View applicant/s information',
            self::VIEW_EMPLOYEE_INFORMATION => 'View employee/s information',
            self::VIEW_EMPLOYEE_DASHBOARD => 'View employee dashboard',
            self::VIEW_ATTENDANCE => 'View attendance',
            self::VIEW_PAYSLIPS => 'View payslips',
            self::VIEW_PERFORMANCE => 'View performance',
            self::VIEW_LEAVES => 'View leaves',
            self::VIEW_OVERTIME => 'View overtime',
            self::VIEW_DOCUMENTS => 'View documents',
            self::VIEW_ISSUES => 'View issues',
            self::VIEW_HR_MANAGER_DASHBOARD => 'View Human Resource manager dashboard',
            self::VIEW_ALL_APPLICANTS => 'View all applicants',
            self::VIEW_ALL_EMPLOYEES => 'View all employees',
            self::VIEW_ALL_ATTENDANCE => 'View all attendance records',
            self::VIEW_ALL_LEAVES => 'View all leave records',
            self::VIEW_ALL_OVERTIME => 'View all overtime records',
            self::VIEW_ALL_PAYSLIPS => 'View all payslips',
            self::VIEW_ALL_PERFORMANCE => 'View all performance records',
            self::VIEW_ALL_RELATIONS => 'View all relations',
            self::VIEW_MATRIX_PROJECTOR => 'View matrix projector',
            self::VIEW_TALENT_EVALUATOR => 'View talent evaluator',
            self::VIEW_PLAN_GENERATOR => 'View plan generator',
            self::VIEW_ADMIN_DASHBOARD => 'View admin dashboard',
            self::VIEW_ACCOUNT_MANAGER => 'View account manager',
            self::VIEW_EMPLOYEE_MANAGER => 'View employee manager',
            self::VIEW_CALENDAR_MANAGER => 'View calendar manager',
            self::VIEW_JOB_LISTING_MANAGER => 'View job listing manager',
            self::VIEW_POLICY_MANAGER => 'View company policies manager',
            self::VIEW_ANNOUNCEMENT_MANAGER => 'View announcement manager',
            self::VIEW_PERFORMANCE_CONFIG => 'View performance configurator',
            self::VIEW_FORM_CONFIG => 'View form configurator',

            // Update labels goes here
            self::UDPATE_JOB_LISTING => 'Update job listing/s',
            self::UPDATE_ANNOUNCEMENT => 'Update announcement/s',

            // Delete labels goes here
            self::DELETE_JOB_LISTING => 'Delete job listing/s',
            self::DELETE_ANNOUNCEMENT => 'Delete announcement/s',
        };
    }
}
