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
    case CREATE_PRE_EMPLOYMENT_DOCUMENT = 'create pre employment document';
    case CREATE_APPLICANT_EXAM_SCHEDULE = 'create applicant exam schedule';
    case CREATE_APPLICANT_INIT_INTERVIEW_SCHEDULE = 'create applicant initial interview schedule';
    case CREATE_APPLICANT_FINAL_INTERVIEW_SCHEDULE = 'create applicant final interview schedule';
    case CREATE_EMPLOYEE_ASSIGNED_SALARY = 'create employee assigned base salary';
    case CREATE_EMPLOYEE_ASSIGNED_SUPERVISOR = 'create employee assigned supervisor';
    case CREATE_JOB_FAMILY = 'create job family';
    case CREATE_JOB_TITLE = 'create job title';
    case CREATE_EMP_PERFORMANCE_EVAL_FORM = 'create employee performance evaluation form';
    case CREATE_ALL_EMP_PERFORMANCE_EVAL_FORM = 'create all employee performance evaluation form';
    case CREATE_EMP_PERFORMANCE_ASSIGNED_TRAINING = 'create employee performance assigned training';
    case CREATE_PERFORMANCE_CATEGORIES = 'create performance categories';
    case CREATE_PERFORMANCE_RATING_SCALES = 'create performance rating scales';
    case CREATE_PREEMPLOYMENT_REQUIREMENTS = 'create pre employment requirements';
    case CREATE_ATTENDANCE_TODAY = 'create attendance today';
    case CREATE_LEAVE_REQUEST = 'create leave request';
    case CREATE_OVERTIME_REQUEST = 'create overtime request';
    case CREATE_ISSUE_COMPLAINT = 'create issue complaint';
    case CREATE_PAYSLIPS = 'create payslips';
    case CREATE_BULK_PAYSLIPS = 'create bulk payslips';

        // View cases goes here
    case VIEW_APPLICATION_INFORMATION = 'view applicant/s information';
    case VIEW_JOB_APPLICATION_FORM = 'view job application form';
    case VIEW_EMPLOYEE_INFORMATION = 'view employee/s information';
    case VIEW_ALL_EMPLOYEE_INFORMATION = 'view all employee information';
    case VIEW_EMPLOYEE_DASHBOARD = 'view employee dashboard';
    case VIEW_DAILY_ATTENDANCE = 'view daily attendance';
    case VIEW_ATTENDANCE_SUMMARY = 'view attendance summary';
    case VIEW_ATTENDANCE_WORKDAYS = 'view attendance workdays';
    case VIEW_ATTENDANCE_WORKHOURS = 'view all attendance records';
    case VIEW_DOWNLOAD_ATTENDANCE = 'view download attendance';
    case VIEW_PAYSLIPS = 'view employee payslips';
    case VIEW_ALL_PAYSLIPS_ARCHIVE = 'view employee payslips archive';
    case VIEW_DOWNLOAD_PAYSLIPS = 'view donwload payslips';
    case VIEW_DOWNLOAD_ALL_PAYSLIPS = 'view download all employee payslips';
    case VIEW_EMP_PERFORMANCE_EVAL = 'view employee performance evaluation';
    case VIEW_EMP_PERFORMANCE_TRAINING = 'view employee performance training';
    case VIEW_LEAVES = 'view employee leaves';
    case VIEW_OVERTIME = 'view employee overtime';
    case VIEW_DOCUMENTS = 'view employee documents';
    case VIEW_ISSUES = 'view employee issues';
    case VIEW_HR_MANAGER_DASHBOARD = 'view hr manager dashboard';
    case VIEW_ALL_PENDING_APPLICATIONS = 'view all pending applications';
    case VIEW_ALL_QUALIFIED_APPLICATIONS = 'view all qualified applications';
    case VIEW_ALL_PRE_EMPLOYED_APPLICATIONS = 'view all pre employed applications';
    case VIEW_ALL_EMPLOYEES = 'view all employees';
    case VIEW_ALL_DAILY_ATTENDANCE = 'view all daily attendance records';
    case VIEW_ALL_ATTENDANCE_TRACKING = 'view all attendance tracking';
    case VIEW_DOWNLOAD_ALL_ATTENDANCE = 'view download all attendance records';
    case VIEW_ALL_ATTENDANCE_SUMMARY = 'view all attendance summaries';
    case VIEW_ALL_ATTENDANCE_WORKDAYS = 'view all attendance by workdays';
    case VIEW_ALL_ATTENDANCE_WORKHOURS = 'view all attendance by workhours';
    case VIEW_DOWNLOAD_PAYROLL_SUMMARY = 'view download all computed attendance summary';
    case VIEW_ALL_LEAVES = 'view all leave requests';
    case VIEW_ALL_OVERTIME_REQUEST = 'view all overtime requests';
    case VIEW_ALL_PAYSLIPS = 'view all payslips';
    case VIEW_ALL_ISSUES = 'view all employee issues';
    case VIEW_ALL_TRAINING = 'view all employee trainings';
    case VIEW_ALL_EMP_PERFORMANCE_EVAL = 'view all performance evaluation records';
    case VIEW_ALL_EMP_PERFORMANCE_TRAINING = 'view all performance training records';
    case VIEW_ALL_EMP_PERFORMANCE_EVAL_GRADE_FORM = 'view all performance evaluation grade form';
    case VIEW_ALL_EMP_PERFORMANCE_EVAL_APPROVAL_FORM = 'view all performance approval form';
    case VIEW_ALL_EMP_PERFORMANCE_EVAL_FINAL_APPROVAL_FORM = 'view all performance final approval form';
    case VIEW_ALL_RELATIONS = 'view all relations';
    case VIEW_TALENT_EVALUATOR = 'view talent evaluator';
    case VIEW_PLAN_GENERATOR = 'view plan generator';
    case VIEW_ADMIN_DASHBOARD = 'view admin dashboard';
    case VIEW_ALL_ACCOUNTS = 'view accounts';
    case VIEW_EMPLOYEE_MANAGER = 'view employee manager';
    case VIEW_CALENDAR_MANAGER = 'view calendar manager';
    case VIEW_JOB_LISTING_MANAGER = 'view job listing manager';
    case VIEW_POLICY_MANAGER = 'view policies manager';
    case VIEW_ANNOUNCEMENT_MANAGER = 'view announcement manager';
    case VIEW_PERFORMANCE_CONFIG = 'view performance configurator';
    case VIEW_FORM_CONFIG = 'view form configurator';
    case VIEW_ONLINE_USERS = 'view online users';
    case VIEW_ALL_SUBORDINATE_REQUESTS = 'view all subordinate requests';
    case VIEW_ALL_SUBORDINATE_PERFORMANCE_EVAL_FORM = 'view all subordinate performance evaluation forms.';
    case VIEW_ALL_SUBORDINATE_LEAVE_REQUEST = 'view all subordinate leaves requests.';
    case VIEW_ALL_SUBORDINATE_OVERTIME_REQUEST = 'view all subordinate overtime requests.';
    case VIEW_ALL_SUBORDINATE_OVERTIME_SUMMARY_FORMS = 'view all subordinate overtime summary forms.';
    case VIEW_SUBORDINATE_PERFORMANCE_EVAL_FORM = 'view subordinate performance evaluation form.';
    case VIEW_SUBORDINATE_LEAVE_REQUEST = 'view subordinate leave request';
    case VIEW_SUBORDINATE_OVERTIME_REQUEST = 'view subordinate overtime request';

        // Update cases goes here
    case UDPATE_JOB_LISTING = 'update job listing';
    case UPDATE_ANNOUNCEMENT = 'update announcement';
    case UPDATE_PENDING_APPLICATION_STATUS = 'update pending application status';
    case UPDATE_QUALIFIED_APPLICATION_STATUS = 'update qualified application status';
    case UPDATE_PRE_EMPLOYED_APPLICATION_STATUS = 'update pre employed application status';
    case UPDATE_PRE_EMPLOYMENT_DOCUMENT = 'update pre employment document';
    case UPDATE_PRE_EMPLOYMENT_DOCUMENT_STATUS = 'update pre employment document status';
    case UPDATE_EMPLOYEE_DOCUMENT = 'update employee document';
    case UPDATE_EMPLOYEE_DOCUMENT_STATUS = 'update employee document status';
    case UPDATE_EMP_PERFORMANCE_EVAL_GRADE_FORM = 'update employee performance evaluation grade form';
    case UPDATE_EMP_PERFORMANCE_EVAL_APPROVAL_FORM = 'update employee performance evaluation approval form';
    case UPDATE_EMP_PERFORMANCE_EVAL_FINAL_APPROVAL_FORM = 'update employee performance evaluation final approval form';
    case UPDATE_LEAVE_BALANCE = 'update leave balance';
    case UPDATE_PERFORMANCE_CATEGORIES = 'update performance categories';
    case UPDATE_PERFORMANCE_RATING_SCALES = 'update performance rating scales';
    case UPDATE_PREEMPLOYMENT_REQUIREMENTS = 'update pre employment requirements';
    case UPDATE_ALL_OVERTIME_REQUEST = 'update every employee overtime request';
    case UPDATE_PENDING_OVERTIME_REQUEST_STATUS = 'update pending overtime request status';
    case UPDATE_APPROVED_OVERTIME_REQUEST_STATUS = 'update approved overtime request status';
    case UPDATE_ISSUE_STATUS = 'update issue status';
    case UPDATE_BIOMETRIC_DEVICE = 'update biometric attendance device configuration';
    case UPDATE_SUBORDINATE_OVERTIME_REQUEST = 'update subordinate overtime request';

        // Delete cases goes here
    case DELETE_JOB_LISTING = 'delete job listing';
    case DELETE_ANNOUNCEMENT = 'delete announcement';
    case DELETE_PRE_EMPLOYMENT_DOCUMENT = 'delete owned pre employment document';

    // Others
    case ASSIGN_PERFORMANCE_EVAL_SCORE = 'assign performance evaluation score';

    case AUTHORIZE_OVERTIME_REQUEST = 'authorize overtime request';
    case APPROVE_OVERTIME_SUMMARY_INITIAL = 'initially approve overtime summary form';
    case APPROVE_OVERTIME_SUMMARY_SECONDARY = 'secondary approve overtime summary form';
    case APPROVE_OVERTIME_SUMMARY_TERTIARY = 'tertiary approve overtime summary form';
    case APPROVE_LEAVE_REQUEST_FIRST = 'first approval for subordinate leave request';
    case APPROVE_LEAVE_REQUEST_SECOND = 'second approval for subordinate leave request';
    case APPROVE_LEAVE_REQUEST_THIRD = 'third approval for any leave request';
    case APPROVE_LEAVE_REQUEST_FOURTH = 'fourth or last approval for any leave request';

    /**
     * Return user-friendly permission labels.
     */
    public function label(): string
    {
        return match ($this) {

            // Create labels goes here
            self::CREATE_JOB_LISTING => 'Publish job listing/s',
            self::CREATE_ANNOUNCEMENT => 'Publish announcement/s',
            self::CREATE_EMPLOYEE_ACCOUNT => 'Create an employee account',
            self::CREATE_BULK_EMPLOYEE_ACCOUNT => 'Create employee accounts',
            self::CREATE_PRE_EMPLOYMENT_DOCUMENT => 'Upload pre-employment document',
            self::CREATE_APPLICANT_EXAM_SCHEDULE => 'Set applicant exam schedule',
            self::CREATE_APPLICANT_INIT_INTERVIEW_SCHEDULE => 'Set applicant initial interview schedule',
            self::CREATE_APPLICANT_FINAL_INTERVIEW_SCHEDULE => 'Set applicant final interview schedule',
            self::CREATE_EMPLOYEE_ASSIGNED_SALARY => 'Set employee base salary',
            self::CREATE_EMPLOYEE_ASSIGNED_SUPERVISOR => 'Assign supervisor to an employee',
            self::CREATE_JOB_FAMILY => 'Create job family',
            self::CREATE_JOB_TITLE => 'Create job title',
            self::CREATE_EMP_PERFORMANCE_EVAL_FORM => 'Create employee performance evaluation form',
            self::CREATE_ALL_EMP_PERFORMANCE_EVAL_FORM => 'Start employee performance evaluation',
            self::CREATE_EMP_PERFORMANCE_ASSIGNED_TRAINING => 'Assign employee performance training',
            self::CREATE_PERFORMANCE_CATEGORIES => 'Create new performance categories',
            self::CREATE_PERFORMANCE_RATING_SCALES => 'Create new performance rating scales',
            self::CREATE_PREEMPLOYMENT_REQUIREMENTS => 'Upload pre-employment requirements',
            self::CREATE_ATTENDANCE_TODAY => 'Set time-in attendance',
            self::CREATE_LEAVE_REQUEST => 'Create a leave request',
            self::CREATE_OVERTIME_REQUEST => 'Create an overtime request',
            self::CREATE_ISSUE_COMPLAINT => 'Create an issue complaint',
            self::CREATE_PAYSLIPS => 'Create a payslip',
            self::CREATE_BULK_PAYSLIPS => 'Create payslips',

            // View labels goes here
            self::VIEW_APPLICATION_INFORMATION => 'View application information',
            self::VIEW_JOB_APPLICATION_FORM => 'View job application form',
            self::VIEW_EMPLOYEE_INFORMATION => 'View employee information',
            self::VIEW_ALL_EMPLOYEE_INFORMATION => 'View all employee information',
            self::VIEW_EMPLOYEE_DASHBOARD => 'View employee dashboard',
            self::VIEW_DAILY_ATTENDANCE => 'View daily attendance',
            self::VIEW_ATTENDANCE_SUMMARY => 'View attendance summary',
            self::VIEW_ATTENDANCE_WORKDAYS => 'View attendance workdays',
            self::VIEW_ATTENDANCE_WORKHOURS => 'View attendance workhours',
            self::VIEW_DOWNLOAD_ATTENDANCE => 'View download attendance',
            self::VIEW_PAYSLIPS => 'View payslips',
            self::VIEW_ALL_PAYSLIPS_ARCHIVE => 'View all payslips archive',
            self::VIEW_DOWNLOAD_PAYSLIPS => 'Download payslips',
            self::VIEW_DOWNLOAD_ALL_PAYSLIPS => 'Download all payslips',
            self::VIEW_EMP_PERFORMANCE_EVAL => 'View employee performance evaluation',
            self::VIEW_EMP_PERFORMANCE_TRAINING => 'View employee performance training',
            self::VIEW_LEAVES => 'View employee leaves',
            self::VIEW_OVERTIME => 'View employee overtime',
            self::VIEW_DOCUMENTS => 'View employee documents',
            self::VIEW_ISSUES => 'View employee issues',
            self::VIEW_HR_MANAGER_DASHBOARD => 'View Human Resource manager dashboard',
            self::VIEW_ALL_PENDING_APPLICATIONS => 'View all pending applications',
            self::VIEW_ALL_QUALIFIED_APPLICATIONS => 'View all qualified applications',
            self::VIEW_ALL_PRE_EMPLOYED_APPLICATIONS => 'View all pre employed applications',
            self::VIEW_ALL_EMPLOYEES => 'View all employees',
            self::VIEW_ALL_DAILY_ATTENDANCE => 'View all daily attendance records',
            self::VIEW_ALL_ATTENDANCE_TRACKING => 'View all attendance tracking',
            self::VIEW_DOWNLOAD_ALL_ATTENDANCE => 'Download all attendance records',
            self::VIEW_ALL_ATTENDANCE_SUMMARY => 'View all attendance summaries',
            self::VIEW_ALL_ATTENDANCE_WORKDAYS => 'View all attendance by workdays',
            self::VIEW_ALL_ATTENDANCE_WORKHOURS => 'View all attendance by workhours',
            self::VIEW_DOWNLOAD_PAYROLL_SUMMARY => 'Download all computed attendance summary',
            self::VIEW_ALL_LEAVES => 'View all leave requests',
            self::VIEW_ALL_OVERTIME_REQUEST => 'View all overtime requests',
            self::VIEW_ALL_PAYSLIPS => 'View all payslips',
            self::VIEW_ALL_EMP_PERFORMANCE_EVAL => 'View all performance evaluation records',
            self::VIEW_ALL_EMP_PERFORMANCE_TRAINING => 'View all performance training records',
            self::VIEW_ALL_EMP_PERFORMANCE_EVAL_GRADE_FORM => 'View all performance evaluation grade form',
            self::VIEW_ALL_EMP_PERFORMANCE_EVAL_APPROVAL_FORM => 'View all performance approval form',
            self::VIEW_ALL_EMP_PERFORMANCE_EVAL_FINAL_APPROVAL_FORM => 'View all performance final approval form',
            self::VIEW_ALL_RELATIONS => 'View all relations',
            self::VIEW_TALENT_EVALUATOR => 'View talent evaluator',
            self::VIEW_PLAN_GENERATOR => 'View plan generator',
            self::VIEW_ADMIN_DASHBOARD => 'View admin dashboard',
            self::VIEW_ALL_ACCOUNTS => 'View accounts',
            self::VIEW_EMPLOYEE_MANAGER => 'View employee manager',
            self::VIEW_CALENDAR_MANAGER => 'View calendar manager',
            self::VIEW_JOB_LISTING_MANAGER => 'View job listing manager',
            self::VIEW_POLICY_MANAGER => 'View company policies manager',
            self::VIEW_ANNOUNCEMENT_MANAGER => 'View announcement manager',
            self::VIEW_PERFORMANCE_CONFIG => 'View performance configurator',
            self::VIEW_FORM_CONFIG => 'View form configurator',
            self::VIEW_ONLINE_USERS => 'Monitor online users',
            self::VIEW_ALL_ISSUES => 'View all employee issues',
            self::VIEW_ALL_TRAINING => 'View all employee trainings',
            self::VIEW_ALL_SUBORDINATE_REQUESTS => 'View all subordinate requests',
            self::VIEW_ALL_SUBORDINATE_PERFORMANCE_EVAL_FORM => 'View all subordinate performance evaluation forms.',
            self::VIEW_ALL_SUBORDINATE_LEAVE_REQUEST => 'View all subordinate leaves requests.',
            self::VIEW_ALL_SUBORDINATE_OVERTIME_REQUEST => 'View all subordinate overtime requests.',
            self::VIEW_ALL_SUBORDINATE_OVERTIME_SUMMARY_FORMS => 'View all subordinate overtime summary forms.',
            self::VIEW_SUBORDINATE_LEAVE_REQUEST => 'View subordinate leave request',
            self::VIEW_SUBORDINATE_OVERTIME_REQUEST => 'View subordinate overtime request',

            // Update labels goes here
            self::UDPATE_JOB_LISTING => 'Update job listing/s',
            self::UPDATE_ANNOUNCEMENT => 'Update announcement/s',
            self::UPDATE_PENDING_APPLICATION_STATUS => 'Update pending application status',
            self::UPDATE_QUALIFIED_APPLICATION_STATUS => 'Update qualified application status',
            self::UPDATE_PRE_EMPLOYED_APPLICATION_STATUS => 'Update pre employed application status',
            self::UPDATE_PRE_EMPLOYMENT_DOCUMENT => 'Update pre-employment document',
            self::UPDATE_PRE_EMPLOYMENT_DOCUMENT_STATUS => 'Update pre-employment document status',
            self::UPDATE_EMPLOYEE_DOCUMENT => 'Update an employee document',
            self::UPDATE_EMPLOYEE_DOCUMENT_STATUS => 'Update employee document status',
            self::UPDATE_EMP_PERFORMANCE_EVAL_GRADE_FORM => 'Update employee performance evaluation grade form',
            self::UPDATE_EMP_PERFORMANCE_EVAL_APPROVAL_FORM => 'Update employee performance evaluation approval form',
            self::UPDATE_EMP_PERFORMANCE_EVAL_FINAL_APPROVAL_FORM => 'Update employee performance evaluation final approval form',
            self::UPDATE_LEAVE_BALANCE => 'Update leave balance',
            self::UPDATE_PERFORMANCE_CATEGORIES => 'Update performance categories',
            self::UPDATE_PERFORMANCE_RATING_SCALES => 'Update performance rating scales',
            self::UPDATE_PREEMPLOYMENT_REQUIREMENTS => 'Update pre-employment requirements',
            self::UPDATE_ALL_OVERTIME_REQUEST => 'Update every employee overtime request status',
            self::UPDATE_PENDING_OVERTIME_REQUEST_STATUS => 'Update pending overtime request status',
            self::UPDATE_APPROVED_OVERTIME_REQUEST_STATUS => 'Update approved overtime request status',
            self::UPDATE_ISSUE_STATUS => 'Update issue complaint status',
            self::UPDATE_BIOMETRIC_DEVICE => 'Update biometric attendance device configuration',
            self::UPDATE_SUBORDINATE_OVERTIME_REQUEST => 'Update (approve/deny) subordinate overtime request.',

            // Delete labels goes here
            self::DELETE_JOB_LISTING => 'Delete job listing/s',
            self::DELETE_ANNOUNCEMENT => 'Delete announcement/s',
            self::DELETE_PRE_EMPLOYMENT_DOCUMENT => 'Delete a pre-employment document',

            // Other labels goes here
            self::ASSIGN_PERFORMANCE_EVAL_SCORE => 'Assign performance evaluation score',

            self::AUTHORIZE_OVERTIME_REQUEST => 'Authorize overtime request',
            self::APPROVE_OVERTIME_SUMMARY_INITIAL => 'Initial approval of overtime summary form',
            self::APPROVE_OVERTIME_SUMMARY_SECONDARY => 'Second approval of overtime summary form',
            self::APPROVE_OVERTIME_SUMMARY_TERTIARY => 'Third approval of overtime summary form',
            self::APPROVE_LEAVE_REQUEST_FIRST => 'First approval of subordinate leave request',
            self::APPROVE_LEAVE_REQUEST_SECOND => 'Second approval of subordinate leave request',
            self::APPROVE_LEAVE_REQUEST_THIRD => 'Third approval of any leave request',
            self::APPROVE_LEAVE_REQUEST_FOURTH => 'Fourth or last approval of any leave request',
        };
    }
}
