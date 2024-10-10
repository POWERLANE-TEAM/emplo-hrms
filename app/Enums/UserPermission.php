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

    // View cases goes here
    case VIEW_APPICANT_INFORMATION = 'view applicant information';
    case VIEW_EMPLOYEE_INFORMATION = 'view employee information';
    case VIEW_EMPLOYEE_DASHBOARD = 'view employee dashboard';

    // Update cases goes here
    case UDPATE_JOB_LISTING = 'update job listing';
    case UPDATE_ANNOUNCEMENT = 'update announcement';

    // Delete cases goes here
    case DELETE_JOB_LISTING = 'delete job listing';
    case DELETE_ANNOUNCEMENT = 'delete announcement';

    // displays user-friendly permission names in blade templates
    public function label(): string
    {
        return match ($this) {

            // Create labels goes here
            self::CREATE_JOB_LISTING => 'Publish job listing/s',
            self::CREATE_ANNOUNCEMENT => 'Publish announcement/s',

            // View labels goes here
            self::VIEW_APPICANT_INFORMATION => 'View applicant/s information',
            self::VIEW_EMPLOYEE_INFORMATION => 'View employee/s information',
            self::VIEW_EMPLOYEE_DASHBOARD => 'View employee dashboard',

            // Update labels goes here
            self::UDPATE_JOB_LISTING => 'Update job listing/s',
            self::UPDATE_ANNOUNCEMENT => 'Update announcement/s',

            // Delete labels goes here
            self::DELETE_JOB_LISTING => 'Delete job listing/s',
            self::DELETE_ANNOUNCEMENT => 'Delete announcement/s',
        };
    }
}
