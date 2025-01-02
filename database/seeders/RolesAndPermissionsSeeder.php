<?php

namespace Database\Seeders;

// Refer to: https://spatie.be/docs/laravel-permission/v6/advanced-usage/seeding

use App\Enums\UserPermission;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = collect([]);

        $allRoles = $permissions->concat($this->basicPermissions())
            ->concat($this->intermediatePermissions())
            ->concat($this->advancedPermissions())
            ->concat($this->managerialPermissions());

        $allRoles->each(function (string $name) {
            Permission::firstOrCreate([
                'name' => $name,
            ]);
        });

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Role::firstOrCreate(['name' => UserRole::BASIC])
            ->givePermissionTo($this->basicPermissions());

        Role::firstOrCreate(['name' => UserRole::INTERMEDIATE])
            ->givePermissionTo($this->intermediatePermissions());

        Role::firstOrCreate(['name' => UserRole::ADVANCED])
            ->givePermissionTo($this->advancedPermissions());
    }

    public static function basicPermissions()
    {
        return [
            UserPermission::VIEW_EMPLOYEE_DASHBOARD->value,
            UserPermission::VIEW_DAILY_ATTENDANCE->value,
            UserPermission::VIEW_PAYSLIPS->value,
            UserPermission::VIEW_EMP_PERFORMANCE_EVAL->value,
            UserPermission::VIEW_LEAVES->value,
            UserPermission::VIEW_OVERTIME->value,
            UserPermission::VIEW_DOCUMENTS->value,
            UserPermission::VIEW_ISSUES->value,
            UserPermission::VIEW_RESIGNATION->value,

            UserPermission::CREATE_LEAVE_REQUEST->value,
            UserPermission::CREATE_OVERTIME_REQUEST->value,
            UserPermission::CREATE_ISSUE_REPORT->value,
        ];
    }

    public static function intermediatePermissions()
    {
        return [
            // View cases goes here
            UserPermission::VIEW_HR_MANAGER_DASHBOARD->value,
            UserPermission::VIEW_APPLICATION_INFORMATION->value,
            UserPermission::VIEW_JOB_APPLICATION_FORM->value,
            UserPermission::VIEW_ALL_PENDING_APPLICATIONS->value,
            UserPermission::VIEW_ALL_QUALIFIED_APPLICATIONS->value,
            UserPermission::VIEW_ALL_PRE_EMPLOYED_APPLICATIONS->value,
            UserPermission::VIEW_ALL_EMPLOYEES->value,
            UserPermission::VIEW_ALL_DAILY_ATTENDANCE->value,
            UserPermission::VIEW_ALL_ATTENDANCE_TRACKING->value,
            UserPermission::VIEW_ALL_ATTENDANCE_WORKDAYS->value,
            UserPermission::VIEW_ALL_ATTENDANCE_WORKHOURS->value,
            UserPermission::VIEW_ALL_ATTENDANCE_SUMMARY->value,
            UserPermission::VIEW_DOWNLOAD_ALL_ATTENDANCE->value,
            UserPermission::VIEW_DOWNLOAD_PAYROLL_SUMMARY->value,
            UserPermission::VIEW_ALL_LEAVES->value,
            UserPermission::VIEW_ALL_OVERTIME_REQUEST->value,
            UserPermission::VIEW_ALL_PAYSLIPS->value,
            UserPermission::VIEW_ALL_EMP_PERFORMANCE_EVAL->value,
            UserPermission::VIEW_ALL_EMP_PERFORMANCE_EVAL_GRADE_FORM->value,
            UserPermission::VIEW_ALL_EMP_PERFORMANCE_EVAL_APPROVAL_FORM->value,
            UserPermission::VIEW_ALL_EMP_PERFORMANCE_EVAL_FINAL_APPROVAL_FORM->value,
            UserPermission::VIEW_ALL_RELATIONS->value,
            UserPermission::VIEW_ALL_ISSUES->value,
            UserPermission::VIEW_ALL_TRAINING->value,
            UserPermission::VIEW_TALENT_EVALUATOR->value,
            UserPermission::VIEW_PLAN_GENERATOR->value,
            UserPermission::VIEW_ALL_FILED_RESIGNATION_LETTERS->value,
            UserPermission::VIEW_ALL_COE_REQUESTS->value,
            UserPermission::VIEW_ANY_INCIDENT_REPORT->value,
            UserPermission::VIEW_REPORTS->value,

            // Create cases goes here
            UserPermission::CREATE_APPLICANT_EXAM_SCHEDULE->value,
            UserPermission::CREATE_APPLICANT_INIT_INTERVIEW_SCHEDULE->value,
            UserPermission::CREATE_APPLICANT_FINAL_INTERVIEW_SCHEDULE->value,
            UserPermission::CREATE_EMPLOYEE_ASSIGNED_SALARY->value,
            UserPermission::CREATE_EMPLOYEE_ASSIGNED_SUPERVISOR->value,
            UserPermission::CREATE_PAYSLIPS->value,
            UserPermission::CREATE_BULK_PAYSLIPS->value,
            UserPermission::CREATE_INCIDENT_REPORT->value,

            // Update cases goes here
            UserPermission::UPDATE_PENDING_APPLICATION_STATUS->value,
            UserPermission::UPDATE_QUALIFIED_APPLICATION_STATUS->value,
            UserPermission::UPDATE_PRE_EMPLOYED_APPLICATION_STATUS->value,
            UserPermission::UPDATE_PENDING_APPLICATION_STATUS->value,
            UserPermission::UPDATE_EMP_PERFORMANCE_EVAL_GRADE_FORM->value,
            UserPermission::UPDATE_EMP_PERFORMANCE_EVAL_APPROVAL_FORM->value,
            UserPermission::UPDATE_EMP_PERFORMANCE_EVAL_FINAL_APPROVAL_FORM->value,
            UserPermission::UPDATE_LEAVE_BALANCE->value,
            UserPermission::UPDATE_PENDING_OVERTIME_REQUEST_STATUS->value,
            UserPermission::UPDATE_APPROVED_OVERTIME_REQUEST_STATUS->value,
            UserPermission::UPDATE_ALL_OVERTIME_REQUEST->value,
            UserPermission::APPROVE_OVERTIME_SUMMARY_TERTIARY->value,
            UserPermission::APPROVE_LEAVE_REQUEST_FOURTH->value,
            UserPermission::UPDATE_ISSUE_STATUS->value,

            // Delete cases goes here

            // other
            UserPermission::MANAGE_INCIDENT_REPORT_COLLABORATORS->value,
        ];
    }

    public static function advancedPermissions()
    {
        return [
            // View cases goes here
            UserPermission::VIEW_ADMIN_DASHBOARD->value,
            UserPermission::VIEW_CALENDAR_MANAGER->value,
            UserPermission::VIEW_ALL_ACCOUNTS->value,
            UserPermission::VIEW_EMPLOYEE_MANAGER->value,
            UserPermission::VIEW_JOB_LISTING_MANAGER->value,
            UserPermission::VIEW_POLICY_MANAGER->value,
            UserPermission::VIEW_ANNOUNCEMENT_MANAGER->value,
            UserPermission::VIEW_PERFORMANCE_CONFIG->value,
            UserPermission::VIEW_FORM_CONFIG->value,
            UserPermission::VIEW_ONLINE_USERS->value,

            // Create cases goes here
            UserPermission::CREATE_JOB_LISTING->value,
            UserPermission::CREATE_ANNOUNCEMENT->value,
            UserPermission::CREATE_EMPLOYEE_ACCOUNT->value,
            UserPermission::CREATE_BULK_EMPLOYEE_ACCOUNT->value,
            UserPermission::CREATE_JOB_FAMILY->value,
            UserPermission::CREATE_JOB_TITLE->value,
            UserPermission::CREATE_PERFORMANCE_CATEGORIES->value,
            UserPermission::CREATE_PERFORMANCE_RATING_SCALES->value,
            UserPermission::CREATE_PREEMPLOYMENT_REQUIREMENTS->value,

            // Update cases goes here
            UserPermission::UPDATE_PERFORMANCE_CATEGORIES->value,
            UserPermission::UPDATE_PERFORMANCE_RATING_SCALES->value,
            UserPermission::UPDATE_PREEMPLOYMENT_REQUIREMENTS->value,
            UserPermission::UPDATE_BIOMETRIC_DEVICE->value,

            // Delete cases goes here
        ];
    }

    public static function managerialPermissions()
    {
        return [
            UserPermission::VIEW_ALL_SUBORDINATE_REQUESTS->value,
            UserPermission::VIEW_ALL_SUBORDINATE_PERFORMANCE_EVAL_FORM->value,
            UserPermission::VIEW_ALL_SUBORDINATE_LEAVE_REQUEST->value,
            UserPermission::VIEW_ALL_SUBORDINATE_OVERTIME_REQUEST->value,
            UserPermission::VIEW_ALL_SUBORDINATE_OVERTIME_SUMMARY_FORMS->value,
            UserPermission::VIEW_SUBORDINATE_PERFORMANCE_EVAL_FORM->value,
            UserPermission::VIEW_SUBORDINATE_LEAVE_REQUEST->value,
            UserPermission::VIEW_SUBORDINATE_OVERTIME_REQUEST->value,
            UserPermission::ASSIGN_PERFORMANCE_EVAL_SCORE->value,
            UserPermission::UPDATE_EMP_PERFORMANCE_EVAL_GRADE_FORM->value,
            UserPermission::UPDATE_EMP_PERFORMANCE_EVAL_GRADE_FORM->value,
            UserPermission::UPDATE_SUBORDINATE_OVERTIME_REQUEST->value,
            UserPermission::AUTHORIZE_OVERTIME_REQUEST->value,
            UserPermission::APPROVE_OVERTIME_SUMMARY_INITIAL->value,
            UserPermission::APPROVE_OVERTIME_SUMMARY_SECONDARY->value,
            UserPermission::APPROVE_LEAVE_REQUEST_FIRST->value,
            UserPermission::APPROVE_LEAVE_REQUEST_SECOND->value,
            UserPermission::APPROVE_LEAVE_REQUEST_THIRD->value,
        ];
    }
}
