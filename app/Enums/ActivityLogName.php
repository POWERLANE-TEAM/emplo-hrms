<?php

namespace App\Enums;

/**
 * This will be used to categorized user/system activity logs by setting the **log_name** column in
 * **activity_logs** table. The spatie/activity-log package migration file indexed this column,
 * therefore making it faster to query, especially for filters and search mechanisms.
 *
 * - `CONFIGURATION`    :   Misc adjustments to accomodate business logic and needs.
 * - `USER_MANAGEMENT`  :   Creating, editing, deleting, suspending, and assigning roles/permissions to users.
 * - `AUTHENTICATION`   :   Sign up/in/out, enabling/disabling two-factor authentication, and login attempts(if possible).
 * - `SESSION`          :   Terminating user or other browser sessions.
 * - `PERFORMANCE`      :   Performance review, approval, evaluation, comments, and recommendations.
 * - `EMPLOYEE`         :   Setting, updating, and removing employee information.
 * - `RECRUITMENT`      :   Job postings, applications, examinations, interviews, and absorbing.
 * - `ATTENDANCE`       :   Employee check-ins/outs.
 * - `LEAVE`            :   Leave application approvals and cancellations.
 * - `SYSTEM`           :   AI-Generated contents, automatic deletions, backups, and automated/routine tasks.
 *
 * See docs: https://spatie.be/docs/laravel-activitylog/v4/introduction
 */
enum ActivityLogName: string
{
    case CONFIGURATION = 'configuration';
    case USER_MANAGEMENT = 'user-management';
    case AUTHENTICATION = 'authentication';
    case SESSION = 'session';
    case PERFORMANCE = 'performance';
    case EMPLOYEE = 'employee';
    case RECRUITMENT = 'recruitment';
    case ATTENDANCE = 'attendance';
    case LEAVE = 'leave';
    case SYSTEM = 'system';
}
