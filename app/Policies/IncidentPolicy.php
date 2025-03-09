<?php

namespace App\Policies;

use App\Enums\UserPermission;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IncidentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Check for user permission to create an incident report.
     */
    public function createIncidentReport(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::CREATE_INCIDENT_REPORT)
            ? Response::allow()
            : Response::deny(__('Sorry, you don\'t have permission to create an incident report.'));
    }

    /**
     * Check if user employee owns the incident report or authorized to view any or he's assigned as collaborator.
     */
    public function updateIncidentReport(User $user, ?Incident $incident = null): Response
    {
        if (is_null($incident)) {
            return $user->hasPermissionTo(UserPermission::VIEW_ANY_INCIDENT_REPORT)
                ? Response::allow()
                : Response::deny(__('Sorry, you don\'t have sufficient permission to perform this action.'));
        }

        return $user->account->reportedIncidents->contains($incident) ||
            $user->hasPermissionTo(UserPermission::VIEW_ANY_INCIDENT_REPORT) ||
            $user->account->sharedIncidentRecords->contains($incident)
                ? Response::allow()
                : Response::deny(); // tangina di ako makaisip ng message
    }

    /**
     * Check for user employee permission to manage incident report collaborators
     */
    public function manageIncidentReportCollaborators(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::MANAGE_INCIDENT_REPORT_COLLABORATORS)
            ? Response::allow()
            : Response::deny(__('Sorry, you don\'t have sufficient permission to add incident collaborator.'));
    }
}
